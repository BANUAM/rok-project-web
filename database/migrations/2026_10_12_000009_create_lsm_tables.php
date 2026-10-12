<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ls_ms', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedInteger('question_limit')->default(10);
            $table->unsignedInteger('duration_minutes')->default(20);
            $table->unsignedInteger('passing_score')->default(70);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        Schema::create('lsm_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lsm_id')->constrained('ls_ms')->cascadeOnDelete();
            $table->text('question');
            $table->string('image_path')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('lsm_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lsm_question_id')->constrained('lsm_questions')->cascadeOnDelete();
            $table->text('option_text');
            $table->boolean('is_correct')->default(false);
            $table->timestamps();
        });

        Schema::create('lsm_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lsm_id')->constrained('ls_ms')->cascadeOnDelete();
            $table->foreignId('trial_candidate_id')->constrained('trial_candidates')->cascadeOnDelete();
            $table->unsignedInteger('total_questions')->default(0);
            $table->unsignedInteger('correct_answers')->default(0);
            $table->decimal('score', 5, 2)->default(0);
            $table->boolean('is_passed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('lsm_attempt_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lsm_attempt_id')->constrained('lsm_attempts')->cascadeOnDelete();
            $table->foreignId('lsm_question_id')->constrained('lsm_questions')->cascadeOnDelete();
            $table->foreignId('selected_option_id')->nullable()->constrained('lsm_options')->nullOnDelete();
            $table->boolean('is_correct')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lsm_attempt_answers');
        Schema::dropIfExists('lsm_attempts');
        Schema::dropIfExists('lsm_options');
        Schema::dropIfExists('lsm_questions');
        Schema::dropIfExists('ls_ms');
    }
};
