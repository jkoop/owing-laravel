<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

final class UniqueCi implements ValidationRule {
	/**
	 * @param string $table Be careful! These parameters won't be escaped
	 * @param string|null $column Be careful! These parameters won't be escaped
	 * @param int|array $ignoreRowId Be careful! These parameters won't be escaped
	 */
	public function __construct(
		private string $table,
		private ?string $column = null,
		private int|array $ignoreRowId = [],
	) {}

	/**
	 * Run the validation rule.
	 *
	 * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
	 */
	public function validate(string $attribute, mixed $value, Closure $fail): void {
		$table = $this->table;
		$column = $this->column ?? $attribute;
		$ignoreRowId = $this->ignoreRowId;
		if (!is_array($ignoreRowId)) {
			$ignoreRowId = [$ignoreRowId];
		}
		$ignoreRowId = implode(",", $ignoreRowId);

		$count = DB::selectOne(
			<<<SQL
				SELECT COUNT(*) AS "count" FROM "$table" WHERE LOWER("$column") IS LOWER(:0) AND "id" NOT IN ($ignoreRowId)
			SQL
			,
			[$value],
		)->count;

		if ($count > 0) {
			$fail("validation.unique")->translate();
		}
	}
}
