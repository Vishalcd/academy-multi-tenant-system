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
        Schema::create('academies', function (Blueprint $table) {
            $table->id();
            // $table->enum('name', ['maharaja_sports_academy', 'kings_sports_club', 'good_shepherd_school'])->default('maharaja_sports_academy');
            $table->string('name')->default('maharaja_sports_academy');
            $table->string('email')->unique();
            $table->string('address');
            $table->string('photo')->default('academie_default.jpg');
            $table->string('favicon')->default('favicon.ico');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academies');
    }
};
