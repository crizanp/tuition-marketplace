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
        Schema::table('users', function (Blueprint $table) {
            $table->string('qualification')->nullable()->after('grade_level');
            $table->string('institution')->nullable()->after('qualification');
            $table->string('location_district')->nullable()->after('institution');
            $table->string('location_place')->nullable()->after('location_district');
            $table->string('location_landmark')->nullable()->after('location_place');
            $table->string('whatsapp')->nullable()->after('location_landmark');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'qualification',
                'institution',
                'location_district',
                'location_place',
                'location_landmark',
                'whatsapp',
            ]);
        });
    }
};
