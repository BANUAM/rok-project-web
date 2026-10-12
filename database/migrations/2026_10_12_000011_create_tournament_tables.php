<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tournaments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('format')->default('single_elimination'); // single_elimination, double_elimination, round_robin, league
            $table->string('banner_path')->nullable();
            $table->string('status')->default('live'); // upcoming, live, completed, cancelled
            $table->string('organizer_name')->default('ROK Esports Official');
            $table->string('tier_required')->default('free'); // free, challenger, immortal
            $table->text('description')->nullable();
            $table->text('rules')->nullable();
            $table->timestamps();
        });

        Schema::create('tournament_teams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_id')->constrained('tournaments')->cascadeOnDelete();
            $table->string('team_name');
            $table->string('logo_path')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->unsignedInteger('seed')->default(1);
            $table->timestamps();
        });

        Schema::create('tournament_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_id')->constrained('tournaments')->cascadeOnDelete();
            $table->unsignedInteger('round_number')->default(1);
            $table->string('round_name')->default('Quarter Finals');
            $table->unsignedInteger('match_number')->default(1);
            $table->foreignId('team1_id')->nullable()->constrained('tournament_teams')->nullOnDelete();
            $table->foreignId('team2_id')->nullable()->constrained('tournament_teams')->nullOnDelete();
            $table->integer('score1')->nullable();
            $table->integer('score2')->nullable();
            $table->foreignId('winner_id')->nullable()->constrained('tournament_teams')->nullOnDelete();
            $table->string('status')->default('scheduled'); // scheduled, live, completed
            $table->dateTime('scheduled_time')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tournament_matches');
        Schema::dropIfExists('tournament_teams');
        Schema::dropIfExists('tournaments');
    }
};
