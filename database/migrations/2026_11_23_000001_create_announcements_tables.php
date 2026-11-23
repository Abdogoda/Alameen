<?php

declare(strict_types=1);

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
        Schema::create('announcements', function (Blueprint $table): void {
            $table->id();
            $table->string('title_ar');
            $table->string('title_en')->nullable();
            $table->string('slug')->unique();
            $table->enum('content_type', ['text', 'video', 'mixed'])->default('text');
            $table->text('summary_ar')->nullable();
            $table->text('summary_en')->nullable();
            $table->longText('body_ar')->nullable();
            $table->longText('body_en')->nullable();

            // Video attributes & Media
            $table->string('video_type')->nullable(); // 'url', 'upload', 'youtube', 'vimeo', 'mux'
            $table->text('video_url')->nullable();
            $table->string('video_mux_playback_id')->nullable();
            $table->text('thumbnail_url')->nullable();

            // Targeting
            $table->json('target_platforms')->nullable(); // ['web', 'mobile', 'admin'] or ['all']
            $table->string('target_audience')->default('all'); // 'all', 'students', 'admins', 'guests'
            $table->json('target_role_ids')->nullable();
            $table->json('target_course_ids')->nullable();

            // Display Mode & Enforcement
            $table->enum('display_mode', ['popup', 'force', 'banner', 'inline'])->default('popup');
            $table->enum('frequency', ['once', 'until_dismissed', 'every_session', 'always'])->default('once');

            // Action button (Call To Action)
            $table->string('button_text_ar')->nullable();
            $table->string('button_text_en')->nullable();
            $table->text('button_url')->nullable();

            // Scheduling & Priority
            $table->boolean('is_active')->default(true)->index();
            $table->integer('priority')->default(0)->index();
            $table->timestamp('start_at')->nullable()->index();
            $table->timestamp('end_at')->nullable()->index();

            $table->foreignId('created_by_admin_id')->nullable()->constrained('admins')->nullOnDelete();
            $table->timestamps();

            $table->index(['is_active', 'display_mode', 'target_audience']);
        });

        Schema::create('announcement_interactions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('announcement_id')->constrained('announcements')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('admin_id')->nullable()->constrained('admins')->cascadeOnDelete();
            $table->string('device_fingerprint')->nullable()->index();
            $table->string('platform')->default('web'); // 'web', 'mobile', 'admin'
            $table->enum('action', ['viewed', 'dismissed', 'acknowledged'])->default('viewed');
            $table->timestamp('viewed_at')->nullable();
            $table->timestamp('dismissed_at')->nullable();
            $table->timestamp('acknowledged_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['announcement_id', 'user_id', 'action']);
            $table->index(['announcement_id', 'admin_id', 'action']);
            $table->index(['announcement_id', 'device_fingerprint', 'action']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcement_interactions');
        Schema::dropIfExists('announcements');
    }
};
