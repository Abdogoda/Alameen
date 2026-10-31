<?php

declare(strict_types=1);

use App\Services\Cms\SettingsService;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations to synchronize platform brand name and course translations.
     */
    public function up(): void
    {
        if (Schema::hasTable('settings')) {
            DB::table('settings')
                ->where('key', 'site_name')
                ->update([
                    'value_ar' => 'منصة الأمين',
                    'value_en' => 'Al-Ameen Platform',
                    'updated_at' => now(),
                ]);

            Cache::forget(SettingsService::CACHE_KEY);
        }

        if (Schema::hasTable('courses')) {
            DB::table('courses')
                ->whereNull('title_en')
                ->orWhere('title_en', '')
                ->update([
                    'title_en' => 'Diploma in Fundamentals & Clinical Applications of Speech Therapy',
                    'description_en' => 'The comprehensive clinical training program to qualify speech therapy specialists and parents with sequential lecture locking and clinical assignments.',
                    'updated_at' => now(),
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No destructive reverse needed.
    }
};
