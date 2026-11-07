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
        Schema::create('admin_activity_logs', function (Blueprint $table) {
            $table->id();

            // Admin who performed the action (nullable for unauthenticated attempts or if admin deleted)
            $table->foreignId('admin_id')
                ->nullable()
                ->constrained('admins')
                ->nullOnDelete();

            // Snapshot data of the admin at time of the event
            $table->string('admin_name')->nullable();
            $table->string('admin_email')->nullable();
            $table->string('role_name')->nullable();

            // Functional categorization
            $table->string('module', 50)->default('general')->index(); // e.g. dashboard, courses, students, receipts, tasks, support, admins, roles, settings, auth, notifications
            $table->string('action', 50)->default('view')->index(); // e.g. view, create, update, delete, approve, reject, grade, login, logout, failed_login, upload, toggle, export

            // Human-readable narrative description
            $table->text('description');

            // Subject polymorphic relation (e.g. Course, User, PaymentReceipt, etc.)
            $table->string('subject_type')->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->string('subject_name')->nullable();

            // HTTP Request context
            $table->string('method', 10)->default('GET');
            $table->text('url');
            $table->string('route_name')->nullable()->index();
            $table->string('ip_address', 45)->nullable()->index();
            $table->text('user_agent')->nullable();

            // Execution result and payload
            $table->string('status', 20)->default('success')->index(); // success, failed, warning
            $table->json('properties')->nullable(); // request params, query params, old/new changes, status code, duration_ms

            $table->timestamps();

            // Composite indexes for fast filtering and analytics
            $table->index(['admin_id', 'created_at']);
            $table->index(['module', 'action', 'created_at']);
            $table->index(['subject_type', 'subject_id']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_activity_logs');
    }
};

