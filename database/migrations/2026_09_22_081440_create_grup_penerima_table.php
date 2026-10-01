<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grup_penerima', function (Blueprint $table) {
            $table->bigIncrements('grup_penerima_id');    

            $table->foreignId('grup_id')
                  ->constrained('grup', 'grup_id')       
                  ->onDelete('cascade');

            $table->foreignId('penerima_id')
                  ->constrained('penerima', 'penerima_id') 
                  ->onDelete('cascade');

            $table->timestamps();

            $table->unique(['grup_id', 'penerima_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grup_penerima');
    }
};