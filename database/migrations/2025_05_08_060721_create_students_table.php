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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->after('id');
            $table->unsignedBigInteger('sport_id')->after('user_id');
            $table->integer('total_fees')->default(0);
            $table->integer('fees_due')->default(0);
            $table->boolean('fees_settle')->default(false);
            $table->timestamps();

            // Add user foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Add sport foreign key constraint
            $table->foreign('sport_id')->references('id')->on('sports')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
