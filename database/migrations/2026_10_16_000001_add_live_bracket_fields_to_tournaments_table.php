<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tournaments', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            $table->unsignedInteger('team_count')->default(8)->after('format');
            $table->longText('bracket_state')->nullable()->after('rules');
            $table->boolean('is_public')->default(true)->after('bracket_state');
        });
    }

    public function down(): void
    {
        Schema::table('tournaments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
            $table->dropColumn(['team_count', 'bracket_state', 'is_public']);
        });
    }
};
