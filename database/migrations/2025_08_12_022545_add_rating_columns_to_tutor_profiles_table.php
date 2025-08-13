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
        Schema::table('tutor_profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('tutor_profiles', 'rating')) {
                $table->decimal('rating', 2, 1)->default(0)->after('availability_status'); // 0.0 to 5.0
            }
            if (!Schema::hasColumn('tutor_profiles', 'total_ratings')) {
                $table->integer('total_ratings')->default(0)->after('rating');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tutor_profiles', function (Blueprint $table) {
            $table->dropColumn(['rating', 'total_ratings']);
        });
    }
};
