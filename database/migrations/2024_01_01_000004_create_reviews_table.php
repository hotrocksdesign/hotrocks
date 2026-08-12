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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->foreignId('band_id')->constrained('bands')->onDelete('cascade');
            $table->foreignId('show_id')->nullable()->constrained('shows')->onDelete('set null');
            $table->string('venue');
            $table->dateTime('show_date');
            $table->string('featured_image')->nullable();
            $table->string('setlist_image')->nullable();
            $table->string('video_url')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
