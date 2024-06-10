<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('app:store-temperature')->hourly();
