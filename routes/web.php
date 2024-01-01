<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\CalculatorController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware("auth")->group(function () {
	Route::get("/", [DashboardController::class, "view"]);

	Route::get("cars", [CarController::class, "index"]);
	Route::get("car/new", [CarController::class, "new"]);
	Route::post("car/new", [CarController::class, "create"]);
	Route::get("car/{car}", [CarController::class, "view"])->withTrashed();
	Route::post("car/{car}", [CarController::class, "update"])
		->withTrashed()
		->can("update", "car");

	Route::middleware("can:isAdmin")->group(function () {
		Route::get("users", [UserController::class, "index"]);
		Route::get("user/new", [UserController::class, "new"]);
		Route::post("user/new", [UserController::class, "create"]);
		Route::get("user/{user}", [UserController::class, "view"])->withTrashed();
		Route::post("user/{user}", [UserController::class, "update"])->withTrashed();
	});

	Route::get("transaction/new", [TransactionController::class, "new"]);
	Route::post("transaction/new", [TransactionController::class, "create"]);
	Route::get("transaction/{transaction}", [TransactionController::class, "view"])->can("view", "transaction");
	Route::post("transaction/{transaction}", [TransactionController::class, "update"])->can("update", "transaction");

	Route::get("profile", [ProfileController::class, "view"]);
	Route::post("profile", [ProfileController::class, "update"]);
	Route::get("change-password", [ProfileController::class, "changePassword"]);
	Route::post("change-password", [ProfileController::class, "doChangePassword"]);

	Route::get("logout", [AuthenticationController::class, "logout"]);

	Route::get("calculate/trip-price", [CalculatorController::class, "tripPrice"]);
});

Route::middleware("guest")->group(function () {
	Route::get("login", [AuthenticationController::class, "view"])->name("login");
	Route::post("login", [AuthenticationController::class, "login"]);
});

Route::view("temp", "pages.temp");
