<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarFuelType extends Model {
	public const FUEL_TYPES = [
		"diesel" => "Diesel",
		"gasoline" => "Gasoline",
	];

	public function author(): BelongsTo {
		return $this->belongsTo(User::class, "author_id")->withTrashed();
	}

	public function getDescriptionAttribute(): string {
		// for change histories
		return "changed fuel_type to " . $this->fuel_type;
	}
}
