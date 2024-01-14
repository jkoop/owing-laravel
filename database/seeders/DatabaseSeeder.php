<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\CarEfficiency;
use App\Models\CarFuelType;
use App\Models\FuelPrice;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
	/**
	 * Seed the application's database.
	 */
	public function run(): void {
		User::create([
			"username" => "admin",
			"password" => "password",
			"name" => "Admin",
			"is_admin" => true,
		]);
		User::create([
			"username" => "other",
			"password" => "password",
			"name" => "Other Guy",
			"is_admin" => false,
		]);

		$car = Car::create([
			"owner_id" => 1,
			"name" => "Admin's Car",
		]);
		CarEfficiency::create(["car_id" => $car->id, "efficiency" => 1]);
		CarFuelType::create(["car_id" => $car->id, "fuel_type" => "gasoline"]);

		foreach (CarFuelType::FUEL_TYPES as $type => $a) {
			FuelPrice::create(["fuel_type" => $type, "price" => 1, "created_at" => 0, "updated_at" => 0]);
		}
	}
}
