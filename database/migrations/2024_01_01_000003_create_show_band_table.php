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
        Schema::create('show_band', function (Blueprint $table) {
            $table->id();
            $table->foreignId('show_id')->constrained('shows')->onDelete('cascade');
            $table->foreignId('band_id')->constrained('bands')->onDelete('cascade');
            $table->integer('order')->default(0);
            $table->unique(['show_id', 'band_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('show_band');
    }
};
