<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hero_slides', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->text('description')->nullable();

            // Lokasi file gambar di storage Laravel
            $table->string('image_path');

            $table->string('cta_primary_text')->default('Join Our Trial');
            $table->string('cta_primary_url')->default('/trial');

            $table->string('cta_secondary_text')->default('Explore Teams');
            $table->string('cta_secondary_url')->default('/teams');

            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hero_slides');
    }
};