<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['band_id']);
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->foreignId('band_id')->nullable()->change();
            $table->foreign('band_id')->references('id')->on('bands')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['band_id']);
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->foreignId('band_id')->nullable(false)->change();
            $table->foreign('band_id')->references('id')->on('bands')->cascadeOnDelete();
        });
    }
};
