<?php

namespace App\Jobs;

use App\Types\Job;
use App\Models\Tip;
use Illuminate\Database\Eloquent\Collection;

class ProcessTipsJob extends Job
{
    /**
     * The fields to retrieve.
     *
     */
    protected array $fields = [
        'tips.id',
        'tips.user_id',
        'tips.slug',
        'tips.summary',
        'tips.first_tag',
    ];

    /**
     * The number of times the job may be attempted.
     *
     */
    public int $tries = 1;

    /**
     * Execute the job.
     *
     */
    public function handle() : void
    {
        Tip::query()
            ->select($this->fields)
            ->join('users', 'tips.user_id', '=', 'users.id')
            ->where('shared', false)
            ->whereBetween('published_at', [now()->subDay(), now()])
            ->chunkById(50, fn($tips) => $this->process($tips));
    }

    /**
     * Handle a batch of tips that are due to be published.
     *
     */
    public function process(Collection $tips) : void
    {
        $query = Tip::whereIn('id', $tips->pluck('id'));

        attempt(fn() => $query->update(['shared' => true]));

        $tips->each(fn($tip) => PostToSocialMediaJob::dispatch($tip));
    }
}
