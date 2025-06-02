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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('academy_id')->after('id');
            $table->string('title');
            $table->text('description');
            $table->string('shop_details');
            $table->enum('payment_type', ['cash', 'online'])->default('cash');
            $table->integer('unit_price');
            $table->integer('quantity')->default(1);
            $table->integer('total_price');
            $table->string('photo');
            $table->boolean('payment_settled')->default(false);
            $table->timestamps();

            // Add academie foreign key constraint
            $table->foreign('academy_id')->references('id')->on('academies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
