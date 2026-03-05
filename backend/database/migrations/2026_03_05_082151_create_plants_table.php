<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('plants', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('scientific_name')->nullable();
            $table->string('local_name_en')->nullable();
            $table->string('local_name_am')->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_am')->nullable();
            $table->text('uses_en')->nullable();
            $table->text('uses_am')->nullable();
            $table->text('preparation_en')->nullable();
            $table->text('preparation_am')->nullable();
            $table->string('region')->nullable();
            $table->text('safety_info_en')->nullable();
            $table->text('safety_info_am')->nullable();
            $table->string('cover_image')->nullable();
            $table->json('image_gallery')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plants');
    }
};
