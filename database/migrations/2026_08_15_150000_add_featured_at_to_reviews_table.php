<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Tracks when a review was marked as featured, so the home
            // carousel can cap how many show at once and evict the
            // longest-standing one first when a new one is added.
            $table->timestamp('featured_at')->nullable()->after('is_featured');
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn('featured_at');
        });
    }
};
