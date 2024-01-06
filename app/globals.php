<?php

use Illuminate\Support\HtmlString;

function c(string $name, array $attributes = []): HtmlString {
	return h(view("components.$name", $attributes)->render());
}

function h(string|HtmlString $trustedString): HtmlString {
	if ($trustedString instanceof HtmlString) {
		return $trustedString;
	}
	return new HtmlString($trustedString);
}

function t(string $key, array $attributes = []): HtmlString {
	$translation = e(__($key));

	foreach ($attributes as $key => $value) {
		$value = e($value);
		$newTranslation = str_replace("{:" . $key . "}", $value, $translation);
		if ($newTranslation == $translation) {
			// the translation didn't have it in braces
			$newTranslation = str_replace(":" . $key, $value, $translation);
		}
		$translation = $newTranslation;
	}

	return h($translation);
}
