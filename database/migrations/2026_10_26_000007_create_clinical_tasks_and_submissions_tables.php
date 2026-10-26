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
        // 1. Clinical Voice Tasks
        Schema::create('clinical_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->foreignId('video_id')->nullable()->constrained('videos')->nullOnDelete();
            $table->string('title_ar');
            $table->string('title_en')->nullable();
            $table->text('instructions_ar')->nullable();
            $table->text('instructions_en')->nullable();
            $table->unsignedInteger('max_score')->default(100);
            $table->timestamps();
        });

        // 2. Student Voice Submissions & Clinical Voice Grading
        Schema::create('task_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('clinical_tasks')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('audio_recording_path');
            $table->text('student_notes')->nullable();
            $table->string('status', 20)->default('pending'); // pending | graded | needs_revision
            $table->unsignedInteger('score')->nullable();
            $table->text('clinical_feedback')->nullable();
            $table->foreignId('graded_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->timestamp('graded_at')->nullable();
            $table->timestamps();

            $table->unique(['task_id', 'user_id']);
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_submissions');
        Schema::dropIfExists('clinical_tasks');
    }
};
