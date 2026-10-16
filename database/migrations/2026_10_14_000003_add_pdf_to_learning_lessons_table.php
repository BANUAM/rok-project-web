<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('learning_lessons', function (Blueprint $table) {
            $table->string('content_type')->default('video')->after('title'); // 'video' or 'pdf'
            $table->string('pdf_path')->nullable()->after('video_url');
            $table->string('pdf_filename')->nullable()->after('pdf_path');
            $table->unsignedBigInteger('file_size')->nullable()->after('pdf_filename'); // in bytes
        });
    }

    public function down(): void
    {
        Schema::table('learning_lessons', function (Blueprint $table) {
            $table->dropColumn(['content_type', 'pdf_path', 'pdf_filename', 'file_size']);
        });
    }
};
