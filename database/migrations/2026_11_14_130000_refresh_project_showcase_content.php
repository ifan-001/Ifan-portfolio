<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Refresh portfolio project content from config/showcase.php and add new
 * projects (Newsifly, AdShield, this platform). Existing rows are matched by
 * slug; admin-added projects are left untouched. Content only, no schema change.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('projects')) {
            return;
        }

        $show = config('showcase.projects', []);
        $order = array_flip(config('showcase.order', []));

        foreach ($show as $slug => $s) {
            if (empty($s['summary'])) {
                continue; // academic entries keep their existing content
            }

            $row = [
                'title' => $s['title'],
                'tagline' => $s['type'] ?? null,
                'summary' => $s['summary'],
                'description' => implode("\n\n", array_map(fn ($f) => $f['title'] . ': ' . $f['desc'], $s['features'] ?? [])),
                'technologies' => json_encode($s['tech'] ?? []),
                'category' => $s['category'] ?? 'web',
                'is_featured' => in_array($slug, ['doctor-qr', 'newsifly'], true),
                'order' => $order[$slug] ?? 99,
                'updated_at' => now(),
            ];

            $existing = DB::table('projects')->where('slug', $slug)->first();
            if ($existing) {
                if (empty($existing->thumbnail) && ! empty($s['gallery'][0]['src'])) {
                    $row['thumbnail'] = $s['gallery'][0]['src'];
                }
                DB::table('projects')->where('id', $existing->id)->update($row);
            } else {
                DB::table('projects')->insert($row + [
                    'slug' => $slug,
                    'thumbnail' => $s['gallery'][0]['src'] ?? null,
                    'live_url' => null,
                    'github_url' => null,
                    'created_at' => now(),
                ]);
            }
        }

        // Keep academic projects after the professional ones
        foreach ($order as $slug => $pos) {
            DB::table('projects')->where('slug', $slug)->update(['order' => $pos]);
        }
    }

    public function down(): void
    {
        // Content refresh only; earlier text is kept in the pre-migration database backup.
    }
};
