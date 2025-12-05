<?php
namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AccountType;
use App\Services\Accounts\AccountLeaf;
use App\Services\Accounts\AccountComposite;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        $accounts = Account::whereNull('parent_id')->get();
        $result = [];
        foreach ($accounts as $acc) {
            $composite = new AccountComposite($acc);
            $result[] = $composite->getDetails();
        }
        return response()->json($result);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id'=>'required|exists:users,id',
            'account_type_id'=>'required|exists:account_types,id',
            'parent_id'=>'nullable|exists:accounts,id',
        ]);

        $data['uuid'] = \Illuminate\Support\Str::uuid();

        $account = Account::create($data);
        return response()->json(['message'=>'Account created','account'=>$account]);
    }

    public function show($id)
    {
        $account = Account::findOrFail($id);
        $composite = $account->children()->exists() ? new AccountComposite($account) : new AccountLeaf($account);
        return response()->json($composite->getDetails());
    }

    public function addChild(Request $request, $id)
    {
        $parent = Account::findOrFail($id);
        $child = Account::findOrFail($request->child_id);
        $composite = new AccountComposite($parent);
        $composite->add(new AccountLeaf($child));
        return response()->json(['message'=>'Child account added','parent'=>$composite->getDetails()]);
    }
}
