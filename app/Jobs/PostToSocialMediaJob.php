<?php

namespace App\Jobs;

use App\Types\Job;
use App\Models\Tip;
use App\Services\Twitter\Tweet;

class PostToSocialMediaJob extends Job
{
    /**
     * The tip to be posted.
     *
     */
    public Tip $tip;

    /**
     * The number of times the job may be attempted.
     *
     */
    public int $tries = 1;

    /**
     * Constructor.
     *
     */
    public function __construct(Tip $tip)
    {
        $this->tip = $tip;
    }

    /**
     * Execute the job.
     *
     */
    public function handle() : void
    {
        if (filled($this->tip->user->integration)) {
            Tweet::publish($this->tip);
        }
    }
}
