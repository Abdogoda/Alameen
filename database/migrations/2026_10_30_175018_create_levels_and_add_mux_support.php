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
        // 1. Levels table for dynamic course curriculum hierarchy
        if (! Schema::hasTable('levels')) {
            Schema::create('levels', function (Blueprint $table) {
                $table->id();
                $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
                $table->string('title_ar');
                $table->string('title_en')->nullable();
                $table->text('description_ar')->nullable();
                $table->text('description_en')->nullable();
                $table->unsignedInteger('sort_order')->default(1);
                $table->timestamps();

                $table->index(['course_id', 'sort_order']);
            });
        }

        // 2. Add compare price, promo video and Mux fields to courses
        Schema::table('courses', function (Blueprint $table) {
            if (! Schema::hasColumn('courses', 'original_price')) {
                $table->decimal('original_price', 10, 2)->nullable()->after('price');
            }
            if (! Schema::hasColumn('courses', 'promo_video_source')) {
                $table->string('promo_video_source', 50)->default('link')->nullable()->after('thumbnail');
            }
            if (! Schema::hasColumn('courses', 'promo_video_url')) {
                $table->text('promo_video_url')->nullable()->after('promo_video_source');
            }
            if (! Schema::hasColumn('courses', 'mux_playback_id')) {
                $table->string('mux_playback_id')->nullable()->after('promo_video_url');
            }
            if (! Schema::hasColumn('courses', 'mux_asset_id')) {
                $table->string('mux_asset_id')->nullable()->after('mux_playback_id');
            }
        });

        // 3. Add level_id and MUX / Link streaming source fields to videos
        Schema::table('videos', function (Blueprint $table) {
            if (! Schema::hasColumn('videos', 'level_id')) {
                $table->foreignId('level_id')->nullable()->after('course_id')->constrained('levels')->nullOnDelete();
            }
            if (! Schema::hasColumn('videos', 'video_source')) {
                $table->string('video_source', 50)->default('mux')->after('slug');
            }
            if (! Schema::hasColumn('videos', 'video_url')) {
                $table->text('video_url')->nullable()->after('video_source');
            }
            if (! Schema::hasColumn('videos', 'mux_playback_id')) {
                $table->string('mux_playback_id')->nullable()->after('video_url');
            }
            if (! Schema::hasColumn('videos', 'mux_asset_id')) {
                $table->string('mux_asset_id')->nullable()->after('mux_playback_id');
            }
            if (! Schema::hasColumn('videos', 'mux_upload_id')) {
                $table->string('mux_upload_id')->nullable()->after('mux_asset_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            if (Schema::hasColumn('videos', 'level_id')) {
                $table->dropForeign(['level_id']);
                $table->dropColumn('level_id');
            }
            $table->dropColumn([
                'video_source',
                'video_url',
                'mux_playback_id',
                'mux_asset_id',
                'mux_upload_id',
            ]);
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn([
                'original_price',
                'promo_video_source',
                'promo_video_url',
                'mux_playback_id',
                'mux_asset_id',
            ]);
        });

        Schema::dropIfExists('levels');
    }
};
