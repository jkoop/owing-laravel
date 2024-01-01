<?php

namespace App\Livewire;

use App\Models\Model;
use Livewire\Component;

class ChangeHistory extends Component {
	private Model $model;

	public function mount(Model $model) {
		$this->model = $model;
	}

	public function render() {
		$model = $this->model;
		return view("livewire.change-history.index", compact("model"));
	}

	public function placeholder() {
		return view("livewire.change-history.placeholder");
	}
}
