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
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number', 32)->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('assigned_admin_id')->nullable()->constrained('admins')->nullOnDelete();
            $table->foreignId('course_id')->nullable()->constrained('courses')->nullOnDelete();
            $table->string('subject');
            $table->string('category', 50)->default('general'); // technical, academic, billing, courses, certificates, general
            $table->string('priority', 20)->default('medium'); // low, medium, high, urgent
            $table->string('status', 30)->default('open'); // open, in_progress, waiting_student, waiting_admin, resolved, closed
            $table->timestamp('last_message_at')->nullable()->index();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['assigned_admin_id', 'status']);
            $table->index(['status', 'priority']);
        });

        Schema::create('support_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('support_ticket_id')->constrained('support_tickets')->cascadeOnDelete();
            $table->nullableMorphs('sender'); // sender_type, sender_id (null for system messages)
            $table->string('type', 30)->default('message'); // message, internal_note, system_event
            $table->text('message');
            $table->json('attachments')->nullable(); // array of [{name, path, size, mime, url}]
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['support_ticket_id', 'created_at']);
            $table->index(['support_ticket_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_messages');
        Schema::dropIfExists('support_tickets');
    }
};
