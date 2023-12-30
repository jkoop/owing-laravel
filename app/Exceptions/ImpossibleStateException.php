<?php

namespace App\Exceptions;

use LogicException;

class ImpossibleStateException extends LogicException {
	public function __construct(string $message = null) {
		$this->message = $message ?? "Impossible state reached!";
	}
}
