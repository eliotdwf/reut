<?php

use App\Jobs\SyncAssosJob;
use Illuminate\Support\Facades\Schedule;

//Schedule::job(new SyncAssosJob())->twiceDaily(); // Runs at 1:00 and 13:00
Schedule::job(new SyncAssosJob())->everyMinute(); // Runs at 1:00 and 13:00
