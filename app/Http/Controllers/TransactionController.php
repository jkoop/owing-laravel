<?php

namespace App\Http\Controllers;

use App\Exceptions\ImpossibleStateException;
use App\Models\Car;
use App\Models\Transaction;
use App\Models\User;
use App\Repositories\FuelPriceRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

final class TransactionController extends Controller {
	public function new(Request $request) {
		$request->validate([
			"clone" => "nullable|int|exists:transactions,id",
		]);

		if ($request->clone) {
			$transaction = Transaction::findOrPanic($request->clone);
			Gate::authorize("view", $transaction);
			$transaction->id = null;
		} else {
			$transaction = new Transaction();
		}

		return view("pages.transaction", compact("transaction"));
	}

	public function create(Request $request) {
		return $this->update(new Transaction(), $request, $returnTo = '/');
	}

	public function view(Transaction $transaction) {
		return view("pages.transaction", compact("transaction"));
	}

	public function update(Transaction $transaction, Request $request, string $returnTo = null) {
		DB::beginTransaction(); // prevent races

		$request->validate([
			"kind" => "required|in:owing,payment,drivetrak",
			"from_to" => "nullable|required_if:kind,owing,payment|in:from,to",
			"car_id" => "nullable|required_if:kind,drivetrak|integer|exists:cars,id",
			"other_user_id" => "nullable|required_if:kind,owing,payment|integer|exists:users,id", // we'll do more validation in a moment
			"distance" => "nullable|required_if:kind,drivetrak|numeric", // we'll do more validation in a moment
			"amount" => "nullable|required_if:kind,owing,payment|numeric", // we'll do more validation in a moment
			"occurred_at" => "required|date",
			"memo" => "present",
		]);

		if ($request->kind != "drivetrak") {
			$otherUser = User::findOrPanic($request->other_user_id);

			$request->validate([
				"amount" => "required|numeric|min:0.01",
			]);

			$fromUser = $request->from_to == "to" ? Auth::user() : $otherUser;
			$toUser = $request->from_to != "to" ? Auth::user() : $otherUser;

			$transaction->fill([
				"kind" => $request->kind,
				"from_user_id" => $fromUser->id,
				"to_user_id" => $toUser->id,
				"amount" => round($request->amount, 2),
				"is_confirmed" => $fromUser->id == Auth::id(),
				"car_id" => null,
				"distance" => null,
				"memo" => trim($request->memo ?? ""),
				"occurred_at" => strtotime($request->occurred_at . " 12:00 America/Winnipeg"),
			]);
			$transaction->save();
			DB::commit();

			return Redirect::to($returnTo ?? "/t/$transaction->id")->with("success", "Saved");
		} elseif ($request->kind == "drivetrak") {
			$car = Car::findOrPanic($request->car_id);
			$fuelPrice = FuelPriceRepository::getFuelPriceAtTime($car->fuel_type, $request->occurred_at);
			$amount = round($car->efficiency * $fuelPrice->price * $request->distance, 2);

			if ($car->owner_id == Auth::id()) {
				$request->validate(["other_user_id" => "required"]);
				$otherUser = User::findOrPanic($request->other_user_id);
			} else {
				$otherUser = $car->owner;
			}

			$request->validate([
				"distance" => "nullable|required_if:kind,drivetrak|numeric|min:0.01",
			]);

			Validator::validate(compact("amount"), ["amount" => "required|numeric|min:0.01"]);

			$fromUser = $car->owner_id == Auth::id() ? Auth::user() : $otherUser;
			$toUser = $car->owner_id != Auth::id() ? Auth::user() : $otherUser;

			$transaction->fill([
				"kind" => "drivetrak",
				"from_user_id" => $fromUser->id,
				"to_user_id" => $toUser->id,
				"amount" => (float) $amount,
				"is_confirmed" => $fromUser->id == Auth::id(),
				"car_id" => $car->id,
				"distance" => round($request->distance, 2),
				"memo" => trim($request->memo ?? ""),
				"occurred_at" => strtotime($request->occurred_at . " 12:00 America/Winnipeg"),
			]);
			$transaction->save();
			DB::commit();

			return Redirect::to($returnTo ?? "/t/$transaction->id")->with("success", "Saved");
		} else {
			throw new ImpossibleStateException();
		}

		throw new ImpossibleStateException();
	}
}
