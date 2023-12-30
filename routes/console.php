<?php

use App\Repositories\FuelPriceRepository;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

Artisan::command("app:freshen-fuel-prices", function () {
	FuelPriceRepository::refreshFuelPrices();
})->purpose("Freshen stale fuel prices");

Artisan::command("db:optimize", function () {
	DB::unprepared("VACUUM;");
})->purpose("Perform routine DB optimizations");
