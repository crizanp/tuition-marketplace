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
        Schema::create('tutor_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tutor_id')->constrained()->onDelete('cascade');
            $table->text('bio')->nullable();
            $table->json('languages')->nullable(); // Store languages with proficiency levels
            $table->string('introduction_video')->nullable();
            $table->json('availability_schedule')->nullable(); // Store weekly schedule
            $table->enum('availability_status', ['available', 'unavailable'])->default('available');
            $table->timestamp('unavailable_until')->nullable();
            $table->json('portfolio_items')->nullable(); // Store portfolio items
            $table->json('additional_certifications')->nullable(); // Store additional certifications
            $table->integer('profile_views')->default(0);
            $table->decimal('rating', 3, 2)->default(0);
            $table->integer('total_ratings')->default(0);
            $table->integer('total_students')->default(0);
            $table->integer('total_hours')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tutor_profiles');
    }
};
