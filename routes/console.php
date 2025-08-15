<?php

use App\Jobs\BiannualDatabaseCleanJob;
use App\Jobs\SyncAssosJob;
use Illuminate\Support\Facades\Schedule;

Schedule::job(new SyncAssosJob())->daily(); // Runs every day at midnight

Schedule::job(new BiannualDatabaseCleanJob())->yearlyOn(8, 20, '00:00');
Schedule::job(new BiannualDatabaseCleanJob())->yearlyOn(2, 01, '00:00');
