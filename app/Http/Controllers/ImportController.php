<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

final class ImportController extends Controller {
	public function view() {
		return view("pages.import");
	}
}
