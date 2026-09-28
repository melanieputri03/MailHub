<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email', function (Blueprint $table) {
            $table->id();

            $table->foreignId('template_id')
                  ->nullable()
                  ->constrained('template_email')
                  ->onDelete('set null');

            $table->string('nama', 150);
            $table->string('subject', 255);
            $table->longText('body');
            $table->string('message_id', 255)->nullable();

            $table->enum('status', ['sent', 'failed'])->default('sent');

            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('sent_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email');
    }
};