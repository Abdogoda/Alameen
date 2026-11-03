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
        Schema::table('levels', function (Blueprint $table) {
            if (! Schema::hasColumn('levels', 'is_published')) {
                $table->boolean('is_published')->default(true)->after('sort_order');
                $table->index(['course_id', 'is_published']);
            }
        });

        Schema::table('videos', function (Blueprint $table) {
            if (! Schema::hasColumn('videos', 'is_published')) {
                $table->boolean('is_published')->default(true)->after('is_free_preview');
                $table->index(['course_id', 'is_published']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('levels', function (Blueprint $table) {
            if (Schema::hasColumn('levels', 'is_published')) {
                $table->dropIndex(['course_id', 'is_published']);
                $table->dropColumn('is_published');
            }
        });

        Schema::table('videos', function (Blueprint $table) {
            if (Schema::hasColumn('videos', 'is_published')) {
                $table->dropIndex(['course_id', 'is_published']);
                $table->dropColumn('is_published');
            }
        });
    }
};
