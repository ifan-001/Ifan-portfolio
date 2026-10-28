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
        Schema::create('feed_posts', function (Blueprint $table) {
            $table->id();
            $table->string('author_name')->default('Hasnain Al Ifan');
            $table->string('title')->nullable();
            $table->string('category')->default('professional')->index(); // professional, social, industrial, project, research
            $table->text('content');
            $table->string('media_type')->default('none'); // none, image, video
            $table->string('media_path')->nullable();
            $table->string('video_url')->nullable();
            $table->unsignedInteger('likes_count')->default(0);
            $table->boolean('is_pinned')->default(false);
            $table->string('status')->default('published')->index(); // published, draft
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feed_posts');
    }
};
