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
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->text('message');
            $table->foreignId('job_id')->nullable()->constrained('tutor_jobs')->onDelete('cascade');
            $table->foreignId('tutor_id')->nullable()->constrained('tutors')->onDelete('cascade');
            $table->foreignId('student_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->enum('type', ['job_inquiry', 'general', 'support'])->default('job_inquiry');
            $table->enum('status', ['unread', 'read', 'responded'])->default('unread');
            $table->text('admin_response')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index(['tutor_id', 'created_at']);
            $table->index(['job_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
    }
};
