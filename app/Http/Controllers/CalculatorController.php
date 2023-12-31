<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Repositories\FuelPriceRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

final class CalculatorController extends Controller {
	public function tripPrice(Request $request) {
		$request->validate([
			"car_id" => "required|int|exists:cars,id",
			"distance" => "required|numeric|min:0.01",
			"date" => "nullable",
		]);

		$dateString = $request->date;

		if (strtotime($request->date) >= 1000000000) {
			$dateString = $dateString . " 12:00 America/Winnipeg";
			$date = new Carbon(date("r", strtotime($dateString)));
		} else {
			$date = now("America/Winnipeg");
		}

		$car = Car::findOrPanic($request->car_id);
		$fuelPrice = FuelPriceRepository::getFuelPriceAtTime($car->fuel_type, $date);

		$answer = number_format($car->efficiency * $fuelPrice->price * $request->distance, 2);
		$answer = '$' . $answer;

		if (strtotime($dateString) < 1000000000) {
			$answer .= " (assuming today)";
		} elseif (
			$date->timestamp > now()->timestamp &&
			$date->format("Y-m-d") != now("America/Winnipeg")->format("Y-m-d")
		) {
			$answer .= " (transactions in the future can change without warning)";
		}

		return response(
			$answer,
			headers: [
				"Content-Type" => "text/plain",
			],
		);
	}
}
