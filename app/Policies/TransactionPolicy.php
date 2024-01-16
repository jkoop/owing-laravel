<?php

namespace App\Policies;

use App\Models\Transaction;
use App\Models\User;

final class TransactionPolicy {
	public function view(User $user, Transaction $transaction) {
		return $transaction->from_user_id == $user->id or $transaction->to_user_id == $user->id;
	}

	public function update(User $user, Transaction $transaction) {
		/**
		 * - it must be a new transaction OR all of the following:
		 *
		 * - we must be involved in the transaction (from_ or to_user)
		 * - if the transaction involves a car,
		 *   - the car must not be deleted
		 *   - the owner of the car must be the from_user
		 */

		if ($transaction->id == null) {
			return true;
		}

		if ($user->id != $transaction->from_user_id and $user->id != $transaction->to_user_id) {
			return false;
		}

		if ($transaction->car_id != null) {
			if ($transaction->car?->deleted_at != null) {
				return false;
			}
			if ($transaction->car->owner_id != $transaction->from_user_id) {
				return false;
			}
		}

		return true;
	}
}
