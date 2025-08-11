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
        Schema::create('tutor_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tutor_id')->constrained()->onDelete('cascade');
            
            // Basic Info
            $table->string('title');
            $table->text('description');
            $table->json('subjects'); // Multiple subjects
            $table->decimal('hourly_rate', 8, 2);
            
            // Location Details
            $table->string('country');
            $table->string('state');
            $table->string('district');
            $table->string('place');
            $table->string('landmark')->nullable();
            $table->string('ward_no')->nullable();
            $table->string('postal_code')->nullable();
            
            // Teaching Preferences
            $table->enum('teaching_mode', ['home', 'online', 'institute', 'any']); // where to teach
            $table->json('preferred_times')->nullable(); // time slots when available
            $table->enum('gender_preference', ['male', 'female', 'any'])->default('any');
            $table->string('student_level')->nullable(); // e.g., primary, secondary, higher secondary
            
            // Additional Info
            $table->json('gallery')->nullable(); // store image paths
            $table->text('requirements')->nullable(); // any special requirements
            $table->integer('max_students')->default(1); // max students per session
            $table->enum('session_type', ['individual', 'group', 'both'])->default('individual');
            
            // Status and Availability
            $table->enum('status', ['active', 'inactive', 'paused'])->default('active');
            $table->boolean('is_featured')->default(false);
            $table->timestamp('expires_at')->nullable();
            
            // Metrics
            $table->integer('views')->default(0);
            $table->integer('inquiries')->default(0);
            
            $table->timestamps();
            
            // Indexes
            $table->index(['tutor_id', 'status']);
            $table->index(['country', 'state', 'district']);
            $table->index(['teaching_mode', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tutor_jobs');
    }
};
