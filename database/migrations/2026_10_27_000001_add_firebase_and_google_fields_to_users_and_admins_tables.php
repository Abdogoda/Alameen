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
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'firebase_uid')) {
                $table->string('firebase_uid')->nullable()->unique()->after('google_id');
            }
        });

        Schema::table('admins', function (Blueprint $table) {
            if (! Schema::hasColumn('admins', 'google_id')) {
                $table->string('google_id')->nullable()->unique()->after('email');
            }
            if (! Schema::hasColumn('admins', 'firebase_uid')) {
                $table->string('firebase_uid')->nullable()->unique()->after('google_id');
            }
            if (! Schema::hasColumn('admins', 'avatar')) {
                $table->string('avatar')->nullable()->after('firebase_uid');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'firebase_uid')) {
                $table->dropColumn('firebase_uid');
            }
        });

        Schema::table('admins', function (Blueprint $table) {
            $columnsToDrop = [];
            if (Schema::hasColumn('admins', 'avatar')) {
                $columnsToDrop[] = 'avatar';
            }
            if (Schema::hasColumn('admins', 'firebase_uid')) {
                $columnsToDrop[] = 'firebase_uid';
            }
            if (Schema::hasColumn('admins', 'google_id')) {
                $columnsToDrop[] = 'google_id';
            }
            if (! empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
