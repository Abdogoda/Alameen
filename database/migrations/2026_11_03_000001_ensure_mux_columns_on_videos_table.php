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
        Schema::table('videos', function (Blueprint $table) {
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

        Schema::table('courses', function (Blueprint $table) {
            if (! Schema::hasColumn('courses', 'mux_playback_id')) {
                $table->string('mux_playback_id')->nullable();
            }
            if (! Schema::hasColumn('courses', 'mux_asset_id')) {
                $table->string('mux_asset_id')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            if (Schema::hasColumn('videos', 'mux_upload_id')) {
                $table->dropColumn('mux_upload_id');
            }
        });
    }
};
