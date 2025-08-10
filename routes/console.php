<?php

use App\Jobs\SyncAssosJob;
use Illuminate\Support\Facades\Schedule;

Schedule::job(new SyncAssosJob())->daily(); // Runs every day at midnight
//Schedule::job(new SyncAssosJob())->everyMinute();
