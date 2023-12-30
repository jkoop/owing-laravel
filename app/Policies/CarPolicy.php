<?php

namespace App\Policies;

use App\Models\Car;
use App\Models\User;

final class CarPolicy {
	public function update(User $user, Car $car) {
		return $user->is_admin or $car->id == null or $user->id == $car->owner_id;
	}
}
