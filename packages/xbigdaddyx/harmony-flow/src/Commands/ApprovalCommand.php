<?php

namespace Xbigdaddyx\HarmonyFlow\Commands;

use Illuminate\Console\Command;

class ApprovalCommand extends Command
{
    public $signature = 'harmony-flow';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
