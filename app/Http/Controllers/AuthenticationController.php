<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

final class AuthenticationController extends Controller {
	public function view() {
		return view("pages.login");
	}

	public function login(Request $request) {
		$request->validate([
			"username" => "required|string",
			"password" => "required|string",
		]);
		$user = User::where("username", $request->username)->first();
		if ($user == null or !password_verify($request->password, $user->password)) {
			return back()->withErrors(["username" => __("auth.failed")]);
		}
		Auth::login($user, $request->has("remember_me"));
		return Redirect::intended()->with("success", "Welcome");
	}

	public function logout() {
		Auth::logout();
		Session::regenerate();
		return Redirect::to("/login")->with("success", "Goodbye");
	}
}
