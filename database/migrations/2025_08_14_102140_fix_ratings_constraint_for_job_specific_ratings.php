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

        $connection = \DB::connection();
        $dbName = $connection->getDatabaseName();

        \DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Drop old index if exists
        $old = \DB::selectOne('SELECT COUNT(1) as cnt FROM information_schema.statistics WHERE table_schema = ? AND table_name = ? AND index_name = ?', [$dbName, 'ratings', 'ratings_user_id_tutor_id_unique']);
        if ($old && $old->cnt) {
            \DB::statement('ALTER TABLE ratings DROP INDEX ratings_user_id_tutor_id_unique');
        }

        // Add new unique constraint on user_id + job_id if missing
        $new = \DB::selectOne('SELECT COUNT(1) as cnt FROM information_schema.statistics WHERE table_schema = ? AND table_name = ? AND index_name = ?', [$dbName, 'ratings', 'ratings_user_id_job_id_unique']);
        if (!($new && $new->cnt)) {
            \DB::statement('ALTER TABLE ratings ADD UNIQUE KEY ratings_user_id_job_id_unique (user_id, job_id)');
        }

        \DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \DB::statement('DELETE FROM ratings');
        $connection = \DB::connection();
        $dbName = $connection->getDatabaseName();

        \DB::statement('SET FOREIGN_KEY_CHECKS=0');

        $new = \DB::selectOne('SELECT COUNT(1) as cnt FROM information_schema.statistics WHERE table_schema = ? AND table_name = ? AND index_name = ?', [$dbName, 'ratings', 'ratings_user_id_job_id_unique']);
        if ($new && $new->cnt) {
            \DB::statement('ALTER TABLE ratings DROP INDEX ratings_user_id_job_id_unique');
        }

        $old = \DB::selectOne('SELECT COUNT(1) as cnt FROM information_schema.statistics WHERE table_schema = ? AND table_name = ? AND index_name = ?', [$dbName, 'ratings', 'ratings_user_id_tutor_id_unique']);
        if (!($old && $old->cnt)) {
            \DB::statement('ALTER TABLE ratings ADD UNIQUE KEY ratings_user_id_tutor_id_unique (user_id, tutor_id)');
        }

        \DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
