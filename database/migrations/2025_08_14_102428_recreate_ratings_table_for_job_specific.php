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
        // Drop the existing ratings table
        Schema::dropIfExists('ratings');
        
        // Recreate the ratings table with job-specific constraints
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('tutor_id')->constrained('tutors')->onDelete('cascade');
            $table->foreignId('job_id')->constrained('tutor_jobs')->onDelete('cascade');
            $table->integer('rating')->default(1);
            $table->text('review')->nullable();
            $table->timestamps();
            
            // One rating per user per job (allows multiple ratings from same user to same tutor if different jobs)
            $table->unique(['user_id', 'job_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the new ratings table
        Schema::dropIfExists('ratings');
        
        // Recreate the old ratings table structure
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('tutor_id')->constrained('tutors')->onDelete('cascade');
            $table->foreignId('job_id')->constrained('tutor_jobs')->onDelete('cascade');
            $table->integer('rating')->default(1);
            $table->text('review')->nullable();
            $table->timestamps();
            
            // Old constraint: one rating per user per tutor
            $table->unique(['user_id', 'tutor_id']);
        });
    }
};
