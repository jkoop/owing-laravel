<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

final class DashboardController extends Controller {
	public function view(Request $request) {
		$request->validate([
			'user_id' => 'nullable|int|exists:users,id',
		]);

		$transactions = Auth::user()
			->transactions()
			->with(["userFrom", "userTo"])
			->orderByDesc("occurred_at");
		if ($request->has("deleted")) {
			$transactions = $transactions->withTrashed();
		}
		if ($request->user_id !== null) {
			$transactions = $transactions
				->where(function ($query) use ($request) {
					$query->where('from_user_id', $request->user_id)
						->orWhere('to_user_id', $request->user_id);
				});
		}
		$transactions = $transactions->get();

		$users = DB::select(<<<SQL
				SELECT DISTINCT "from_user_id", "to_user_id"
				FROM "transactions"
				WHERE "from_user_id" = :0
					OR "to_user_id" = :1
			SQL, [Auth::id(), Auth::id()]);
		$users = collect($users)
			->map(fn ($a) => [$a->from_user_id, $a->to_user_id])
			->flatten()
			->filter(fn ($a) => $a != Auth::id())
			->unique();
		$users = User::whereIn('id', $users->toArray())->get();

		return view("pages.dashboard", compact("transactions", "users"));
	}
}
