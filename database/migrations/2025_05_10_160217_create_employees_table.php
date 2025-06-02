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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->after('id');
            $table->unsignedBigInteger('sport_id')->after('user_id')->nullable();
            // employee set to coach only
            $table->enum('job_title', ['coach'])->default('coach');
            $table->integer('salary')->default(0);
            $table->integer('pending_salary')->default(0);
            $table->timestamp('last_paid')->nullable()->default(null);
            $table->boolean('salary_settled')->default(false);
            $table->timestamps();

            // Add user foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Add sport foreign key constraint
            $table->foreign('sport_id')->references('id')->on('sports')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
