<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_penerima', function (Blueprint $table) {
            $table->bigIncrements('email_penerima_id');   

            $table->foreignId('email_id')
                  ->constrained('email', 'email_id')     
                  ->onDelete('cascade');

            $table->foreignId('penerima_id')
                  ->constrained('penerima', 'penerima_id') 
                  ->onDelete('cascade');

            $table->timestamps();

            $table->unique(['email_id', 'penerima_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_penerima');
    }
};