<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class SetLocale {
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
	 */
	public function handle(Request $request, Closure $next): Response {
		$locale = $request->user()?->locale;
		$locale ??= $_COOKIE["locale"] ?? null;
		$locale ??= "en_CA";
		app()->setLocale($locale);

		setcookie("locale", $locale, time() + 60 * 60 * 24 * 365, "/");

		return $next($request);
	}
}
