<?php

namespace App\Policies;

use App\Models\Transaction;
use App\Models\User;

final class TransactionPolicy {
	public function view(User $user, Transaction $transaction) {
		return $transaction->from_user_id == $user->id or $transaction->to_user_id == $user->id;
	}

	public function update(User $user, Transaction $transaction) {
		return $transaction->id == null or // for new transactions
			$transaction->from_user_id == $user->id or
			$transaction->to_user_id == $user->id;
	}
}
