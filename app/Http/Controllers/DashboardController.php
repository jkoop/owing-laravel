<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class DashboardController extends Controller {
	public function view(Request $request) {
		$transactions = Auth::user()
			->transactions()
			->with(["userFrom", "userTo"])
			->orderByDesc("occurred_at");
		if ($request->has("deleted")) {
			$transactions = $transactions->withTrashed();
		}
		$transactions = $transactions->get();
		return view("pages.dashboard", compact("transactions"));
	}
}
