<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trial_candidates', function (Blueprint $table) {
            $table->string('next_stage_token', 64)->nullable()->unique()->after('access_token');
            $table->timestamp('decision_email_sent_at')->nullable()->after('reviewed_at');
            $table->string('decision_email_type')->nullable()->after('decision_email_sent_at'); // 'passed' or 'rejected'
        });
    }

    public function down(): void
    {
        Schema::table('trial_candidates', function (Blueprint $table) {
            $table->dropUnique(['next_stage_token']);
            $table->dropColumn(['next_stage_token', 'decision_email_sent_at', 'decision_email_type']);
        });
    }
};
