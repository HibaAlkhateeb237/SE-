<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id(); // bigIncrements -> unsignedBigInteger
            $table->uuid('tx_id')->unique();

            $table->foreignId('account_id')->constrained('accounts')->onDelete('cascade'); // الربط مع accounts
            $table->enum('type', ['deposit','withdrawal','transfer','fee','interest','scheduled']);
            $table->decimal('amount', 20, 4);
            $table->string('currency',3)->default('USD');
            $table->json('meta')->nullable();
            $table->enum('status', ['pending','authorized','completed','failed','reversed'])->default('pending');
            $table->foreignId('initiated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
