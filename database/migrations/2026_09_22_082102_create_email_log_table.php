<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('email_id')
                  ->constrained('email')
                  ->onDelete('cascade');
            $table->foreignId('penerima_id')
                  ->nullable()
                  ->constrained('penerima')
                  ->onDelete('set null');
            $table->string('penerima_email', 150);   
            $table->enum('status', ['success', 'failed']);
            $table->text('error_message')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->index('email_id');
            $table->index('penerima_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_log');
    }
};