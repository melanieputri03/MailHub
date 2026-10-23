<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('template_email', function (Blueprint $table) {
            $table->longText('header_html')->nullable()->after('body');
            $table->longText('footer_html')->nullable()->after('header_html');
            $table->longText('header_css')->nullable()->after('footer_html');
            $table->longText('footer_css')->nullable()->after('header_css');
        });
    }

    public function down(): void
    {
        Schema::table('template_email', function (Blueprint $table) {
            $table->dropColumn(['header_html', 'footer_html', 'header_css', 'footer_css']);
        });
    }
};