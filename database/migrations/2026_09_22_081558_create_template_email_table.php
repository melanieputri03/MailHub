<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('template_email', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->string('deskripsi', 255)->nullable();
            $table->string('subject', 255)->nullable();
            $table->longText('body')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('template_email');
    }
};