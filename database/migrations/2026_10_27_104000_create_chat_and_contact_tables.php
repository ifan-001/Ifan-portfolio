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
        // 1. Admin Live Status Table
        Schema::create('chat_statuses', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_online')->default(true);
            $table->string('status_text')->default('Active Now');
            $table->timestamp('last_active_at')->nullable();
            $table->timestamps();
        });

        // Seed initial active status
        DB::table('chat_statuses')->insert([
            'is_online' => true,
            'status_text' => 'Active Now',
            'last_active_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Chat Conversations
        Schema::create('chat_conversations', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->unique()->index();
            $table->string('visitor_name')->nullable();
            $table->string('visitor_email')->nullable();
            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();
        });

        // 3. Chat Messages
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained('chat_conversations')->onDelete('cascade');
            $table->enum('sender', ['visitor', 'admin'])->default('visitor');
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });

        // 4. Contact Inquiries Table
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('subject')->nullable();
            $table->text('message');
            $table->string('ip')->nullable();
            $table->boolean('is_replied')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('chat_messages');
        Schema::dropIfExists('chat_conversations');
        Schema::dropIfExists('chat_statuses');
    }
};
