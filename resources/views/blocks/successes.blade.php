@php
	$successes = Session::get('successes', []);
	$success = Session::get('success', null);
	if ($success != null) {
	    $successes[] = $success;
	}
@endphp

@if (!empty($successes))
	@vite('resources/css/successes.css')
	@vite('resources/js/successes.js')

	<div id="successes">
		@foreach ($successes as $success)
			{{ $success }}<br>
		@endforeach
	</div>
@endif

@php
	Session::forget('success');
@endphp
