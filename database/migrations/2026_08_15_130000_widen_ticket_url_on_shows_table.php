<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Real ticket links people paste in are often Instagram/Facebook
        // "link in bio" redirects wrapped in tracking params (fbclid,
        // utm_*, etc.) that blow well past 255 chars — that was causing
        // "Data too long for column 'ticket_url'" on show submission.
        // Raw ALTER instead of Schema::change() to avoid a doctrine/dbal
        // dependency the project doesn't otherwise need.
        DB::statement('ALTER TABLE shows MODIFY ticket_url TEXT NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE shows MODIFY ticket_url VARCHAR(255) NULL');
    }
};
