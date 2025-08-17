<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify enum to include suspended and banned
        DB::statement("ALTER TABLE `users` MODIFY `status` ENUM('active','inactive','suspended','banned') NOT NULL DEFAULT 'active'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum values (active, inactive)
        DB::statement("ALTER TABLE `users` MODIFY `status` ENUM('active','inactive') NOT NULL DEFAULT 'active'");
    }
};
