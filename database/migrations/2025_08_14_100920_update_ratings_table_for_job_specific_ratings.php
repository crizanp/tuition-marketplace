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
        // Use raw SQL to safely drop and recreate constraints
        \DB::unprepared('
            ALTER TABLE ratings DROP INDEX ratings_user_id_tutor_id_unique;
            ALTER TABLE ratings ADD UNIQUE KEY ratings_user_id_job_id_unique (user_id, job_id);
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \DB::unprepared('
            ALTER TABLE ratings DROP INDEX ratings_user_id_job_id_unique;
            ALTER TABLE ratings ADD UNIQUE KEY ratings_user_id_tutor_id_unique (user_id, tutor_id);
        ');
    }
};
