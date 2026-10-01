<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penerima', function (Blueprint $table) {
            $table->bigIncrements('penerima_id');         
            $table->string('nama', 150);
            $table->string('email', 150)->unique();

            $table->foreignId('divisi_id')
                  ->constrained('divisi', 'divisi_id')    
                  ->onDelete('restrict');

            $table->string('jabatan', 150)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            $table->index('email');
            $table->index('divisi_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penerima');
    }
};