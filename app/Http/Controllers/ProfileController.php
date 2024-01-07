<?php

namespace App\Http\Controllers;

use App\Rules\UniqueCi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

final class ProfileController extends Controller {
	public function view() {
		return view("pages.user", ["user" => Auth::user(), "profile" => true]);
	}

	public function update(Request $request) {
		$request->validate([
			"username" => [
				"required",
				"string",
				"ascii",
				"lowercase",
				'regex:/^[a-z0-9][a-z0-9-]*[a-z0-9]$/i',
				new UniqueCi("users", ignoreRowId: Auth::id()),
			],
			"name" => ["required", "string", "ascii", new UniqueCi("users", ignoreRowId: Auth::id())],
			"password" => "nullable|string|min:8",
		]);

		$data = [
			"username" => strtolower($request->username),
			"name" => $request->name,
		];

		if ($request->password != null) {
			$data["password"] = $request->password;
		}

		Auth::user()->update($data);

		return Redirect::back()->with("success", t("Saved"));
	}

	public function changePassword() {
		if (Auth::user()->must_change_password == false) {
			return Redirect::to("/profile");
		}
		return view("pages.change-password");
	}

	public function doChangePassword(Request $request) {
		$request->validate([
			"password" => "required|string|min:8",
		]);

		Auth::user()->update([
			"password" => $request->password,
			"must_change_password" => false,
		]);

		return Redirect::intended()->with("success", t("Saved"));
	}
}
