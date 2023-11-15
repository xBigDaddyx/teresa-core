<?php

namespace Teresa\CartonBoxGuard\Commands;

use Illuminate\Console\Command;

class CartonBoxGuardCommand extends Command
{
    public $signature = 'carton-box-guard';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
