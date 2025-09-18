<?php

use Illuminate\Support\Facades\Schedule;


Schedule::command('get:news tasnim')->everyMinute();
Schedule::command('get:news mehr')->everyMinute();