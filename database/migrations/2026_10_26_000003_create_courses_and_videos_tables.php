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
        // 1. Courses Table
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title_ar');
            $table->string('title_en')->nullable();
            $table->string('slug')->unique();
            $table->text('description_ar')->nullable();
            $table->text('description_en')->nullable();
            $table->decimal('price', 10, 2)->default(0.00);
            $table->unsignedInteger('access_duration_days')->default(365);
            $table->string('thumbnail')->nullable();
            $table->boolean('is_published')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // 2. Time-Bound Course Subscriptions (course_user)
        Schema::create('course_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->timestamp('access_starts_at')->useCurrent();
            $table->timestamp('access_expires_at')->index();
            $table->string('status', 20)->default('active'); // active | expired | suspended
            $table->foreignId('granted_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'course_id']);
        });

        // 3. Videos Table with Bunny.net and Sequential Lock requirement
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->string('title_ar');
            $table->string('title_en')->nullable();
            $table->string('slug');
            $table->string('bunny_video_id')->nullable();
            $table->string('bunny_library_id')->nullable();
            $table->unsignedInteger('duration_seconds')->default(0);
            $table->unsignedTinyInteger('min_completion_percentage')->default(80); // Requirement
            $table->unsignedInteger('sort_order')->default(1);
            $table->boolean('is_free_preview')->default(false);
            $table->timestamps();

            $table->index(['course_id', 'sort_order']);
        });

        // 4. Video Watch Progress
        Schema::create('video_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('video_id')->constrained('videos')->cascadeOnDelete();
            $table->unsignedInteger('watched_seconds')->default(0);
            $table->decimal('completion_percentage', 5, 2)->default(0.00);
            $table->boolean('is_completed')->default(false);
            $table->timestamp('last_watched_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'video_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_progress');
        Schema::dropIfExists('videos');
        Schema::dropIfExists('course_user');
        Schema::dropIfExists('courses');
    }
};
