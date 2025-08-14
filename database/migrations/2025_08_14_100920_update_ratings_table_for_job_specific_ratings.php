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
            $connection = \DB::connection();
            $dbName = $connection->getDatabaseName();

            // check if old index exists
            $old = \DB::selectOne('SELECT COUNT(1) as cnt FROM information_schema.statistics WHERE table_schema = ? AND table_name = ? AND index_name = ?', [$dbName, 'ratings', 'ratings_user_id_tutor_id_unique']);
            if ($old && $old->cnt) {
                \DB::statement('ALTER TABLE ratings DROP INDEX ratings_user_id_tutor_id_unique');
            }

            // add new index if it doesn't already exist
            $new = \DB::selectOne('SELECT COUNT(1) as cnt FROM information_schema.statistics WHERE table_schema = ? AND table_name = ? AND index_name = ?', [$dbName, 'ratings', 'ratings_user_id_job_id_unique']);
            if (!($new && $new->cnt)) {
                \DB::statement('ALTER TABLE ratings ADD UNIQUE KEY ratings_user_id_job_id_unique (user_id, job_id)');
            }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $connection = \DB::connection();
        $dbName = $connection->getDatabaseName();

        $new = \DB::selectOne('SELECT COUNT(1) as cnt FROM information_schema.statistics WHERE table_schema = ? AND table_name = ? AND index_name = ?', [$dbName, 'ratings', 'ratings_user_id_job_id_unique']);
        if ($new && $new->cnt) {
            \DB::statement('ALTER TABLE ratings DROP INDEX ratings_user_id_job_id_unique');
        }

        $old = \DB::selectOne('SELECT COUNT(1) as cnt FROM information_schema.statistics WHERE table_schema = ? AND table_name = ? AND index_name = ?', [$dbName, 'ratings', 'ratings_user_id_tutor_id_unique']);
        if (!($old && $old->cnt)) {
            \DB::statement('ALTER TABLE ratings ADD UNIQUE KEY ratings_user_id_tutor_id_unique (user_id, tutor_id)');
        }
    }
};
