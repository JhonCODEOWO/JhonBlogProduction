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
        Schema::create('article_image', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('article_id');
            $table->unsignedBigInteger('image_id')->nullable();
            $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
            $table->foreign('image_id')->references('id')->on('images')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_image');
    }
};
