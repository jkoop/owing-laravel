<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

final class OwingTotals extends Component {
	public function render() {
		$users = User::withTrashed()
			->where("id", "!=", Auth::id())
			->orderBy("name")
			->get();
		return view("livewire.owing-totals.index", compact("users"));
	}

	public function placeholder() {
		$users = User::where("id", "!=", Auth::id())
			->orderBy("name")
			->get();
		return view("livewire.owing-totals.placeholder", compact("users"));
	}
}
