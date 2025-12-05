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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id(); // auto-increment primary key
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('account_type_id')->constrained('account_types')->onDelete('restrict');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->decimal('balance', 20, 4)->default(0);
            $table->string('currency', 3)->default('USD');
            $table->enum('state', ['active','frozen','suspended','closed'])->default('active');
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('accounts')->onDelete('set null');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
