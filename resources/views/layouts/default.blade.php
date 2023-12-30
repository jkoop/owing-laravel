<!DOCTYPE html>
<html lang="en-CA">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
	<title>@yield('title') - {{ config('app.name') }}</title>
	<link type="image/svg+xml" href="/favicon.svg?v={{ filemtime(public_path('/favicon.svg')) }}" rel="icon" />
	@vite('resources/css/app.css')
	@vite('resources/js/app.js')
</head>

<body>
	<header>
		<nav>
			@if (Auth::check())
				<a href="/">Dashboard</a>
				<a href="/cars">Cars</a>
			@endif
			@can('isAdmin')
				<a href="/users">Users</a>
			@endcan
			@if (Auth::check())
				<a href="/profile">Profile</a>
				<a href="/logout">Logout</a>
			@endif
		</nav>
	</header>
	<main>
		<h1>@yield('title')</h1>
		@include('blocks.successes')
		@include('blocks.errors')
		@yield('content')
	</main>
	<footer>
		@if (Auth::check())
			{{ Auth::user()->name }}
			<a href="/logout">Logout</a>
		@endif
	</footer>
</body>

</html>
