<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarEfficiency extends Model {
	protected $casts = [
		"efficiency" => "float",
	];

	public function author(): BelongsTo {
		return $this->belongsTo(User::class, "author_id")->withTrashed();
	}

	public function getDescriptionAttribute(): string {
		// for change histories
		return "changed efficiency to " . number_format($this->efficiency, 4);
	}
}
