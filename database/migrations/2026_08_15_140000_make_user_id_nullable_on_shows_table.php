<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Bands can now submit a show without an account, so there's no
        // user to attach it to. Raw MODIFY (not Schema::change(), which
        // needs doctrine/dbal) — leaves the existing FK constraint intact,
        // just allows NULL.
        DB::statement('ALTER TABLE shows MODIFY user_id BIGINT UNSIGNED NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE shows MODIFY user_id BIGINT UNSIGNED NOT NULL');
    }
};
