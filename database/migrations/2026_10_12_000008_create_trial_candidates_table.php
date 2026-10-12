<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trial_candidates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('access_token', 64)->unique();
            $table->timestamp('token_expires_at')->nullable();

            // Profil Lengkap
            $table->string('nickname')->nullable();
            $table->string('address')->nullable();
            $table->integer('age')->nullable();
            $table->string('birthplace')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('role')->nullable(); // Gold Laner, Mid Laner, Jungler, EXP Laner, Roamer
            $table->foreignId('ml_tier_id')->nullable()->constrained('ml_tiers')->nullOnDelete();
            $table->integer('stars')->default(0);

            // Berkas Upload (Screenshot Profil, Hero Favorit, Tier)
            $table->string('profile_screenshot_path')->nullable();
            $table->string('favorite_hero_screenshot_path')->nullable();
            $table->string('tier_screenshot_path')->nullable();

            // Status Seleksi: registered, profile_completed, lsm_completed, under_review, passed, rejected
            $table->string('status')->default('registered');
            $table->timestamp('profile_completed_at')->nullable();
            $table->timestamp('lsm_completed_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('reviewer_notes')->nullable();
            $table->boolean('contract_completed')->default(false);
            $table->foreignId('created_player_id')->nullable()->constrained('players')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trial_candidates');
    }
};
