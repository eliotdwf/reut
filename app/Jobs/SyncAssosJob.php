<?php

namespace App\Jobs;

use App\Services\SyncAssosService;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SyncAssosJob implements ShouldQueue, ShouldBeUnique
{
    use Queueable;

    /**

     * The number of seconds after which the job's unique lock will be released.
     *
     * @var int
     */
    public $uniqueFor = 86400; // 1 day

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        app(SyncAssosService::class)->sync();
    }
}
