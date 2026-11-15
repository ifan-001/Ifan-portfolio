<?php

namespace App\Console\Commands;

use App\Services\AppIconGenerator;
use Illuminate\Console\Command;

class RefreshAppIcon extends Command
{
    protected $signature = 'portfolio:app-icon';

    protected $description = 'Rebuild the web-app icons from the current portfolio profile photo.';

    public function handle(): int
    {
        if (! AppIconGenerator::available()) {
            $this->error('The PHP GD extension is not available, icons cannot be generated here.');

            return self::FAILURE;
        }

        if (! AppIconGenerator::fromCurrentProfile()) {
            $this->error('Could not build the icons (is there a profile photo?).');

            return self::FAILURE;
        }

        $this->info('App icons rebuilt: public/icons/app-512.png, app-192.png, app-180.png');

        return self::SUCCESS;
    }
}
