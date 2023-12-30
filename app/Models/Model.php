<?php

namespace App\Models;

use App\Exceptions\ImpossibleStateException;
use Illuminate\Database\Eloquent\Model as EloquentModel;

class Model extends EloquentModel {
	protected $dateFormat = "U";
	protected $guarded = [];

	/**
	 * Find a model by its primary key or throw an `ImpossibleStateException`
	 * @param int|string $id
	 * @return Model
	 * @throws ImpossibleStateException
	 */
	public static function findOrPanic(int|string $id): self {
		return self::find($id) ?? throw new ImpossibleStateException();
	}
}
