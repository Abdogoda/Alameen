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
        // 1. Dynamic Platform Settings
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->mediumText('value_ar')->nullable();
            $table->mediumText('value_en')->nullable();
            $table->string('group', 50)->default('general'); // general | contact | payment | social | branding
            $table->boolean('is_secret')->default(false);
            $table->timestamps();

            $table->index('group');
        });

        // 2. Dynamic Content Blocks / CMS Sections (Hero, FAQ, About, etc.)
        Schema::create('content_blocks', function (Blueprint $table) {
            $table->id();
            $table->string('section_key')->unique();
            $table->string('title_ar')->nullable();
            $table->string('title_en')->nullable();
            $table->text('subtitle_ar')->nullable();
            $table->text('subtitle_en')->nullable();
            $table->longText('content_ar')->nullable();
            $table->longText('content_en')->nullable();
            $table->json('metadata')->nullable(); // For dynamic FAQ Q&As, counters, buttons
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 3. Menus (Header, Footers)
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location')->unique(); // header | footer_quick | footer_support
            $table->timestamps();
        });

        // 4. Menu Items
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained('menus')->cascadeOnDelete();
            $table->string('title_ar');
            $table->string('title_en')->nullable();
            $table->string('url');
            $table->string('route_name')->nullable();
            $table->string('target', 20)->default('_self');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['menu_id', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_items');
        Schema::dropIfExists('menus');
        Schema::dropIfExists('content_blocks');
        Schema::dropIfExists('settings');
    }
};
