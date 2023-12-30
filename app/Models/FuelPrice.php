<?php

namespace App\Models;

final class FuelPrice extends Model {
	protected $casts = [
		"price" => "float",
	];

	public function getIsStaleAttribute(): bool {
		return $this->created_at < now()->subHours(8);
	}
}
