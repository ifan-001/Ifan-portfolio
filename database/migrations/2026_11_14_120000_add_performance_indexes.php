<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->index(['sender', 'is_read'], 'chat_messages_sender_is_read_index');
            $table->index(['conversation_id', 'created_at'], 'chat_messages_conversation_created_index');
        });

        Schema::table('chat_conversations', function (Blueprint $table) {
            $table->index('last_message_at', 'chat_conversations_last_message_at_index');
        });

        Schema::table('contact_messages', function (Blueprint $table) {
            $table->index('status', 'contact_messages_status_index');
        });

        Schema::table('analytics_events', function (Blueprint $table) {
            $table->index('created_at', 'analytics_events_created_at_index');
        });
    }

    public function down(): void
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->dropIndex('chat_messages_sender_is_read_index');
            $table->dropIndex('chat_messages_conversation_created_index');
        });
        Schema::table('chat_conversations', fn (Blueprint $t) => $t->dropIndex('chat_conversations_last_message_at_index'));
        Schema::table('contact_messages', fn (Blueprint $t) => $t->dropIndex('contact_messages_status_index'));
        Schema::table('analytics_events', fn (Blueprint $t) => $t->dropIndex('analytics_events_created_at_index'));
    }
};
