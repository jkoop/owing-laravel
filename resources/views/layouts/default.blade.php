<!DOCTYPE html>
<html lang="en-CA">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
	<title>@yield('title') - {{ config('app.name') }}</title>
	<link type="image/svg+xml" href="/favicon.svg?v={{ filemtime(public_path('/favicon.svg')) }}" rel="icon" />
	<link href="https://fonts.googleapis.com" rel="preconnect">
	<link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Slabo+27px&display=swap" rel="stylesheet">
	@vite('resources/css/app.css')
	@vite('resources/js/app.js')
</head>

<body>
	<hidden>
		<x-spinner id="spinner" />
	</hidden>
	<header>
		<nav>
			@auth
				<a href="/">Dashboard</a>
				<a href="/c">Cars</a>
			@endauth
			@can('isAdmin')
				<a href="/u">Users</a>
			@endcan
			@auth
				<a class="ml-auto" href="/profile">Profile</a>
				<a href="/logout">Logout</a>
			@endauth
		</nav>
	</header>
	<main>
		<h1>@yield('title')</h1>
		@include('blocks.successes')
		@include('blocks.errors')
		@yield('content')
	</main>
	<footer>
		<span>Page took {{ number_format(microtime(true) - LARAVEL_START, 2) }}s</span>
		@auth
			<span><a href="/profile">{{ Auth::user()->name }}</a></span>
		@endauth
	</footer>
</body>

</html>
