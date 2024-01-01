<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Rules\UniqueCi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

final class CarController extends Controller {
	public function index(Request $request) {
		if ($request->has("deleted")) {
			return view("pages.cars", ["cars" => Car::withTrashed()->get()]);
		}

		return view("pages.cars", ["cars" => Car::all()]);
	}

	public function new() {
		return view("pages.car", ["car" => new Car()]);
	}

	public function create(Request $request) {
		return $this->update(new Car(), $request);
	}

	public function view(Car $car) {
		return view("pages.car", compact("car"));
	}

	public function update(Car $car, Request $request) {
		$request->validate([
			"name" => ["required", "string", "ascii", new UniqueCi("cars", ignoreRowId: $car->id ?? [])],
			"efficiency" => "required|numeric|min:0.0001|max:10",
			"fuel_type" => "required|in:" . implode(",", array_keys(Car::FUEL_TYPES)),
			"owner_id" => "nullable|integer|exists:users,id",
		]);

		$data = [
			"name" => $request->name,
			"efficiency" => round($request->efficiency, 4),
			"fuel_type" => $request->fuel_type,
			"owner_id" => Auth::user()->is_admin ? $request->owner_id : Auth::id(),
		];

		if ($data["owner_id"] != null) {
			$data["owner_id"] = (int) $data["owner_id"];
		}

		if ($car->id) {
			$car->update($data);
			if ($request->has("delete")) {
				$car->delete();
			}
			if ($request->has("restore")) {
				$car->restore();
			}
			return Redirect::back()->with("success", "Saved");
		} else {
			$car->fill($data);
			$car->save();
			return Redirect::to("/c/" . $car->id)->with("success", "Saved");
		}
	}
}
