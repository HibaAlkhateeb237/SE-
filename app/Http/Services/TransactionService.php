<?php

namespace App\Http\Services;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Http\Repositories\TransactionRepositoryInterface;
use App\Http\Repositories\AccountRepositoryInterface;
use App\Chains\TransactionApproval\{AccountOwnershipHandler,
    AccountStatusHandler,
    BalanceCheckHandler,
    ManagerApprovalHandler};

class TransactionService
{
    public function __construct(
        protected TransactionRepositoryInterface $transactionRepo,
        protected AccountRepositoryInterface $accountRepo
    ) {}

    /* ==========================================================
       🔗 Chains
    ========================================================== */

    private function depositChain(): AccountOwnershipHandler
    {
        $owner   = new AccountOwnershipHandler();
        $status  = new AccountStatusHandler();
      //  $manager = new ManagerApprovalHandler();

        $owner->setNext($status);

        return $owner;
    }


    private function withdrawChain(): AccountOwnershipHandler
    {
        $owner   = new AccountOwnershipHandler();
        $status  = new AccountStatusHandler();
        $balance = new BalanceCheckHandler();
        $manager = new ManagerApprovalHandler();

        $owner
            ->setNext($status)
            ->setNext($balance)
            ->setNext($manager);

        return $owner;
    }

    private function transferChain(): AccountOwnershipHandler
    {
        return $this->withdrawChain();
    }




    private function approvalChain(): AccountStatusHandler
    {
        //$owner   = new AccountOwnershipHandler();
        $status  = new AccountStatusHandler();
        $balance = new BalanceCheckHandler();

        $status
            ->setNext($status)
            ->setNext($balance);

        return $status;
    }








    /* ==========================================================
       💰 Deposit
       POST /api/transactions/deposit
    ========================================================== */

    public function deposit(int $accountId, float $amount, int $userId)
    {
        $account = $this->accountRepo->findId($accountId);

        // 🔗 Chain of Responsibility
        $this->depositChain()->handle($account, $amount);

        DB::transaction(function () use ($account, $amount) {
            $account->deposit($amount);
        });

        return $this->transactionRepo->create([
            'tx_id'        => Str::uuid(),
            'account_id'  => $account->id,
            'type'        => 'deposit',
            'amount'      => $amount,
            'currency' => $account->currency,
            'status'      => 'completed',
            'initiated_by'=> $userId,
        ]);
    }

    /* ==========================================================
       💸 Withdraw
       POST /api/transactions/withdraw
    ========================================================== */

    public function withdraw(int $accountId, float $amount, int $userId)
    {
        $account = $this->accountRepo->findId($accountId);

        try {
        // 🔗 Chain of Responsibility
        $this->withdrawChain()->handle($account, $amount);

        DB::transaction(function () use ($account, $amount) {
            $account->withdraw($amount);

        });
            $status = 'completed';
            }

        catch (Exception $e) {

            if ($e->getMessage() === 'Manager approval required') {
                $status = 'pending';
            } else {
                throw $e;
            }

        }

        return $this->transactionRepo->create([
            'tx_id'        => Str::uuid(),
            'account_id'  => $account->id,
            'type'        => 'withdrawal',
            'amount'      => $amount,
            'status'      =>$status,
            'initiated_by'=> $userId,
        ]);

    }

    /* ==========================================================
       🔁 Transfer
       POST /api/transactions/transfer
    ========================================================== */

    public function transfer(
        int $fromAccountId,
        int $toAccountId,
        float $amount,
        int $userId
    ) {
        $from = $this->accountRepo->findId($fromAccountId);
        $to   = $this->accountRepo->findId($toAccountId);

        try {


        $this->transferChain()->handle($from, $amount);


        DB::transaction(function () use ($from, $to, $amount) {
            $from->withdraw($amount);
            $to->deposit($amount);
        });


            $status = 'completed';
        }catch (Exception $e) {

            if ($e->getMessage() === 'Manager approval required') {
                $status = 'pending';
            } else {
                throw $e;
            }
        }

        return $this->transactionRepo->create([
            'tx_id'              => Str::uuid(),
            'account_id'        => $from->id,
            'related_account_id'=> $to->id,
            'type'              => 'transfer',
            'amount'            => $amount,
            'status'            => $status,
            'initiated_by'=> $userId,
        ]);
    }

    /* ==========================================================
       📜 Transaction History
       GET /api/transactions
    ========================================================== */

    public function list()
    {
        return $this->transactionRepo->all();
    }




    public function approve(int $transactionId)
    {
        $tx = $this->transactionRepo->findPending($transactionId);
        $managerId = auth()->id();
        DB::transaction(function () use ($tx, $managerId) {

            $account = $this->accountRepo->findId($tx->account_id);

            $this->approvalChain()->handle($account, $tx->amount);

            if ($tx->type === 'withdrawal') {
                $account->withdraw($tx->amount);
            }

            if ($tx->type === 'transfer') {
                $to = $this->accountRepo->findId($tx->related_account_id);
                $account->withdraw($tx->amount);
                $to->deposit($tx->amount);
            }

            $tx->update([
                'status'       => 'completed',
            ]);
        });

        return $tx;
    }



    public function reject(int $transactionId,string $reason = null)
    {
        $tx = $this->transactionRepo->findPending($transactionId);

        $tx->update([
            'status'      => 'failed',
            //'approved_by' => $managerId,
            'meta'    => [
                'reason' => $reason
            ]
        ]);

        return $tx;
    }





    public function getMyTransactions()
    {
        return $this->transactionRepo->getByUser(Auth::id());
    }





}
