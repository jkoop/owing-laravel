<?php

namespace App\Models;

use App\Traits\Changeable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Transaction extends Model {
	use Changeable, SoftDeletes;

	protected $casts = [
		"is_confirmed" => "boolean",
		"occurred_at" => "datetime",
	];

	public function changes(): HasMany {
		return $this->hasMany(Change::class, "transaction_id");
	}

	public function car(): BelongsTo {
		return $this->belongsTo(Car::class, "car_id")->withTrashed();
	}

	public function userFrom(): BelongsTo {
		return $this->belongsTo(User::class, "from_user_id")->withTrashed();
	}

	public function userTo(): BelongsTo {
		return $this->belongsTo(User::class, "to_user_id")->withTrashed();
	}

	public function otherUser(): BelongsTo {
		if ($this->from_user_id == Auth::id()) {
			return $this->userTo();
		} else {
			return $this->userFrom();
		}
	}

	public function getCreditAttribute(): float {
		if ($this->from_user_id == Auth::id()) {
			return $this->amount * -1;
		} else {
			return $this->amount;
		}
	}
}
