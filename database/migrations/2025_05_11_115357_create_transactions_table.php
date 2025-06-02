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
            $table->id();
            $table->unsignedBigInteger('user_id')->after('id')->nullable();
            $table->unsignedBigInteger('expense_id')->after('user_id')->nullable();
            $table->unsignedBigInteger('academy_id')->after('expense_id')->nullable();
            $table->enum('transaction_type', ['withdrawal', 'deposit'])->default('withdraw');
            $table->integer('transaction_amount')->default(0);
            $table->enum('transaction_method', ['cash', 'online'])->default('cash');
            $table->enum('transaction_for', ['student_fee', 'employee_salary', 'expense',])->default('student_fee');
            $table->timestamps();

            // Add user foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('expense_id')->references('id')->on('expenses')->onDelete('cascade');

            // Add academie foreign key constraint
            $table->foreign('academy_id')->references('id')->on('academies')->onDelete('cascade');
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
