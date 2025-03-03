<?php

namespace App\Repositories;

use App\Models\CarFuelType;
use App\Models\FuelPrice;
use App\Models\Transaction;
use Carbon\Carbon;

class FuelPriceRepository {
	private static $prices = [];

	private static function getTypes(): array {
		return array_keys(CarFuelType::FUEL_TYPES);
	}

	public static function getAllFuelPrices(): object {
		$prices = [];

		foreach (self::getTypes() as $type) {
			$prices[$type] = self::getFuelPrice($type);
		}

		return (object) $prices;
	}

	public static function getFuelPrice(string $type): FuelPrice {
		return self::$prices[$type] ??= (function () use ($type) {
			return self::getFuelPriceAtTime($type, now());
		})();
	}

	public static function getFuelPriceAtTime(string $type, string|Carbon $time): FuelPrice {
		if (!in_array($type, self::getTypes())) {
			throw new \InvalidArgumentException('Invalid $type');
		}
		if (!$time instanceof Carbon) {
			$time = Carbon::parse(Carbon::parse($time)->format("Y-m-d") . " 12:00:00 America/Winnipeg");
		}

		return FuelPrice::where("fuel_type", $type)
			->where("created_at", "<", $time->timestamp)
			->orderByDesc("created_at")
			->firstOrFail();
	}

	public static function refreshFuelPrices(): void {
		foreach (self::getTypes() as $type) {
			$price = self::getFreshFuelPrice($type);

			if ($price != null) {
				FuelPrice::create([
					"price" => $price,
					"fuel_type" => $type,
				]);

				Transaction::with("car")
					->where("kind", "drivetrak")
					->where("occurred_at", ">", now()->timestamp)
					->get()
					->map(fn($transaction) => $transaction->recalculate());
			}
		}

		self::$prices = [];
	}

	private static function getFreshFuelPrice(string $type): ?float {
		$type = ["gasoline" => 1, "diesel" => 4][$type];

		exec(
			'curl "https://www.gasbuddy.com/graphql" -s -H "content-type: application/json" --data-raw \'{"operationName":"LocationByArea","variables":{"area":"steinbach","countryCode":"CA","fuel":' .
				$type .
				',"regionCode":"MB"},"query":"query LocationByArea($area: String, $countryCode: String, $criteria: Criteria, $fuel: Int, $regionCode: String) { locationByArea( area: $area countryCode: $countryCode criteria: $criteria regionCode: $regionCode ) { stations(fuel: $fuel) { results { prices(fuel: $fuel) { cash { price } credit { price } fuelProduct } } } }}"}\'',
			$price,
		);

		$price = json_decode($price[0] ?? "null");
		$price = $price?->data->locationByArea->stations->results;
		if ($price == null) {
			return null;
		}

		$price = array_map(function ($result) {
			return $result->prices[0]->credit->price;
		}, $price);
		$price = max(...$price);

		return round($price / 100, 3);
	}
}
