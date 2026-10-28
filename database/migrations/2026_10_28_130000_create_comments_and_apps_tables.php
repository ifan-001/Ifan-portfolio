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
        // 1. Comments on Feed Posts
        Schema::create('feed_post_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('feed_post_id')->constrained('feed_posts')->onDelete('cascade');
            $table->string('user_name');
            $table->string('user_email')->nullable();
            $table->text('comment');
            $table->boolean('is_admin')->default(false);
            $table->string('status')->default('approved'); // approved, pending, spam
            $table->timestamps();
        });

        // 2. Interactive Apps & Tools (Games, Resume Builder, QR Medical ID, etc.)
        Schema::create('interactive_apps', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category')->default('Utility');
            $table->text('short_description');
            $table->string('icon')->default('🚀');
            $table->string('badge')->nullable();
            $table->string('route_name')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('order_index')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interactive_apps');
        Schema::dropIfExists('feed_post_comments');
    }
};
