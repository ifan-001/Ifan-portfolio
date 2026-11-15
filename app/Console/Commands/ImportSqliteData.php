<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Copies the portfolio data from a local SQLite file into the configured
 * (MySQL) database. Run `php artisan migrate` first so the tables exist.
 */
class ImportSqliteData extends Command
{
    protected $signature = 'portfolio:import-sqlite
        {path=database/database.sqlite : Path to the SQLite database file}
        {--content-only : Skip visitor data (chats, inquiries, analytics, comments, push devices)}
        {--force : Do not ask for confirmation}';

    protected $description = 'Import portfolio content from a SQLite file into the current database (SQLite to MySQL).';

    /** Never copied: framework runtime tables. */
    private const RUNTIME_TABLES = [
        'migrations', 'sessions', 'cache', 'cache_locks', 'jobs', 'job_batches',
        'failed_jobs', 'password_reset_tokens', 'sqlite_sequence',
    ];

    /** Visitor generated data, skipped with --content-only. */
    private const VISITOR_TABLES = [
        'chat_conversations', 'chat_messages', 'contact_messages',
        'analytics_events', 'push_subscriptions', 'feed_post_comments',
    ];

    public function handle(): int
    {
        $path = $this->argument('path');
        if (! str_starts_with($path, '/') && ! preg_match('/^[A-Za-z]:[\\\\\/]/', $path)) {
            $path = base_path($path);
        }
        if (! is_file($path)) {
            $this->error("SQLite file not found: {$path}");
            return self::FAILURE;
        }

        $target = DB::connection();
        if ($target->getDriverName() === 'sqlite') {
            $this->error('The current database is SQLite. Set DB_CONNECTION=mysql in .env and run the migrations first.');
            return self::FAILURE;
        }

        config(['database.connections.import_source' => [
            'driver' => 'sqlite',
            'database' => $path,
            'prefix' => '',
            'foreign_key_constraints' => false,
        ]]);
        $source = DB::connection('import_source');

        $skip = self::RUNTIME_TABLES;
        if ($this->option('content-only')) {
            $skip = array_merge($skip, self::VISITOR_TABLES);
        }

        $tables = collect($source->select("SELECT name FROM sqlite_master WHERE type = 'table' AND name NOT LIKE 'sqlite_%' ORDER BY name"))
            ->pluck('name')
            ->reject(fn ($t) => in_array($t, $skip, true))
            ->filter(fn ($t) => Schema::connection($target->getName())->hasTable($t))
            ->values();

        if ($tables->isEmpty()) {
            $this->error('No matching tables found. Did you run php artisan migrate?');
            return self::FAILURE;
        }

        $this->line('Source : ' . $path);
        $this->line('Target : ' . $target->getDatabaseName() . ' (' . $target->getDriverName() . ')');
        $this->line('Tables : ' . $tables->implode(', '));

        if (! $this->option('force') && ! $this->confirm('This REPLACES the data in the tables above. Continue?', false)) {
            $this->warn('Cancelled.');
            return self::FAILURE;
        }

        Schema::connection($target->getName())->disableForeignKeyConstraints();

        try {
            foreach ($tables as $table) {
                $columns = Schema::connection($target->getName())->getColumnListing($table);
                $target->table($table)->truncate();

                $copied = 0;
                $source->table($table)->orderByRaw('1')->chunk(200, function ($rows) use ($target, $table, $columns, &$copied) {
                    $batch = $rows->map(fn ($row) => array_intersect_key((array) $row, array_flip($columns)))->all();
                    if ($batch) {
                        $target->table($table)->insert($batch);
                        $copied += count($batch);
                    }
                });

                $this->line(sprintf('  %-24s %d rows', $table, $copied));
            }
        } finally {
            Schema::connection($target->getName())->enableForeignKeyConstraints();
        }

        $this->info('Import finished.');
        return self::SUCCESS;
    }
}
