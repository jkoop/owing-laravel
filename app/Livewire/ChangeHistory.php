<?php

namespace App\Livewire;

use App\Models\Car;
use App\Models\CarEfficiency;
use App\Models\CarFuelType;
use App\Models\Change;
use App\Models\Model;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
final class ChangeHistory extends Component {
	private Model $model;

	public function mount(Model $model) {
		$this->model = $model;
	}

	public function render() {
		$changes = $this->model
			->changes()
			->with("author")
			->get();
		if ($this->model instanceof Car) {
			$changes = $changes->concat(
				CarEfficiency::with("author")
					->where("car_id", $this->model->id)
					->get(),
			);
			$changes = $changes->concat(
				CarFuelType::with("author")
					->where("car_id", $this->model->id)
					->get(),
			);
		}
		$changes = $changes
			->sortByDesc("id")
			->sortBy(fn($a) => $a instanceof Change)
			->sortByDesc("created_at");
		return view("livewire.change-history.index", compact("changes"));
	}

	public function placeholder() {
		return view("livewire.change-history.placeholder");
	}
}
