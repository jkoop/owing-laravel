@if ($errors->count() > 0)
	@vite('resources/css/errors.css')
@endif

@php
	$errors = $errors->toArray();
	$standaloneErrors = array_filter($errors, 'is_numeric', ARRAY_FILTER_USE_KEY);
	$validationErrors = array_filter($errors, fn($a) => !is_numeric($a), ARRAY_FILTER_USE_KEY);
@endphp

@if (!empty($errors))
	<div id="errors">
		@foreach ($standaloneErrors as $error)
			{{ $error }}<br>
		@endforeach

		@if (count($validationErrors) == 1)
			@t('A validation error occurred. Please check the form and try again.')
		@elseif (count($validationErrors) > 1)
			@t('Validation errors occurred. Please check the form and try again.')
		@endif
	</div>
@endif
