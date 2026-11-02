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
        Schema::table('courses', function (Blueprint $table) {
            $table->string('certificate_template', 50)->default('royal_gold')->after('sort_order');
            $table->string('certificate_custom_bg')->nullable()->after('certificate_template');
            $table->string('certificate_signatory_name')->nullable()->after('certificate_custom_bg');
            $table->string('certificate_signatory_title')->nullable()->after('certificate_signatory_name');
        });

        Schema::table('certificates', function (Blueprint $table) {
            $table->string('template', 50)->nullable()->default('royal_gold')->after('pdf_path');
            $table->string('custom_bg')->nullable()->after('template');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn([
                'certificate_template',
                'certificate_custom_bg',
                'certificate_signatory_name',
                'certificate_signatory_title',
            ]);
        });

        Schema::table('certificates', function (Blueprint $table) {
            $table->dropColumn(['template', 'custom_bg']);
        });
    }
};
