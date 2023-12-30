<?php

namespace App\Models;

use App\Traits\Changeable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Car extends Model {
	use Changeable, SoftDeletes;

	public const FUEL_TYPES = [
		"diesel" => "Diesel",
		"gasoline" => "Gasoline",
	];

	protected $casts = [
		"efficiency" => "float",
	];

	public function changes(): HasMany {
		return $this->hasMany(Change::class, "car_id");
	}

	public function owner(): BelongsTo {
		return $this->belongsTo(User::class, "owner_id")->withTrashed();
	}

	public function transactions(): HasMany {
		return $this->hasMany(Transaction::class, "car_id")->withTrashed();
	}
}
