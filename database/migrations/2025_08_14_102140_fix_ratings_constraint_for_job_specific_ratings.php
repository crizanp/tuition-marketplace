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
        // Clear any existing ratings first to avoid constraint conflicts
        \DB::statement('DELETE FROM ratings');
        
        // Drop and recreate the table with proper constraints
        \DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Drop the old unique constraint
        \DB::statement('ALTER TABLE ratings DROP INDEX ratings_user_id_tutor_id_unique');
        
        // Add new unique constraint on user_id + job_id
        \DB::statement('ALTER TABLE ratings ADD UNIQUE KEY ratings_user_id_job_id_unique (user_id, job_id)');
        
        \DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \DB::statement('DELETE FROM ratings');
        \DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        \DB::statement('ALTER TABLE ratings DROP INDEX ratings_user_id_job_id_unique');
        \DB::statement('ALTER TABLE ratings ADD UNIQUE KEY ratings_user_id_tutor_id_unique (user_id, tutor_id)');
        
        \DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
