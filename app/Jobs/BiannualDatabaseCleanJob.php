<?php

namespace App\Jobs;

use App\Services\DeleteInactiveUsersService;
use App\Services\DeleteOldBookingsService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class BiannualDatabaseCleanJob implements ShouldQueue
{
    use Queueable;

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
        app(DeleteInactiveUsersService::class)->run();
        app(DeleteOldBookingsService::class)->run();
    }
}
