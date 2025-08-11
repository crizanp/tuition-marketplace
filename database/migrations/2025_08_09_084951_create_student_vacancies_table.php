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
        Schema::create('student_vacancies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->string('subject');
            $table->string('grade_level');
            $table->decimal('budget_min', 8, 2);
            $table->decimal('budget_max', 8, 2);
            $table->json('schedule_days')->nullable(); // ['Monday', 'Wednesday', 'Friday']
            $table->json('schedule_times')->nullable(); // ['9:00 AM - 11:00 AM', '2:00 PM - 4:00 PM']
            $table->integer('duration_hours')->default(1); // Session duration in hours
            $table->enum('location_type', ['online', 'home', 'tutor_place', 'flexible'])->default('flexible');
            $table->text('address')->nullable();
            $table->enum('urgency', ['low', 'medium', 'high'])->default('medium');
            $table->json('requirements')->nullable(); // Additional requirements
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed', 'cancelled'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_vacancies');
    }
};
