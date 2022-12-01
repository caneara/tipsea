<?php

namespace App\Commands;

use App\Jobs\ProcessTipsJob;
use Illuminate\Console\Command;

class ProcessTipsCommand extends Command
{
    /**
     * The command's name and signature.
     *
     */
    protected $signature = 'system:process-tips';

    /**
     * The command's description.
     *
     */
    protected $description = 'Publish scheduled tips and notify followers.';

    /**
     * Execute the console command.
     *
     */
    public function handle() : void
    {
        ProcessTipsJob::dispatch();

        $this->info('The tips are being processed');
    }
}
