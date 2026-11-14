<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Store project case-study data (features, stats, status, galleries) in the
 * database so the admin panel can edit everything. Existing rows are filled
 * from config/showcase.php once; afterwards the database is the source of truth.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->json('showcase')->nullable()->after('technologies');
            $table->json('gallery')->nullable()->after('showcase');
            $table->json('mobile_gallery')->nullable()->after('gallery');
        });

        $show = config('showcase.projects', []);
        $keys = ['tagline', 'status', 'year', 'accent', 'icon', 'role', 'problem', 'stats', 'features'];

        foreach (DB::table('projects')->get() as $row) {
            $s = $show[$row->slug] ?? null;
            if (! $s) {
                continue;
            }
            DB::table('projects')->where('id', $row->id)->update([
                'showcase' => json_encode(array_intersect_key($s, array_flip($keys))),
                'gallery' => json_encode($s['gallery'] ?? []),
                'mobile_gallery' => json_encode($s['mobile_gallery'] ?? []),
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['showcase', 'gallery', 'mobile_gallery']);
        });
    }
};
