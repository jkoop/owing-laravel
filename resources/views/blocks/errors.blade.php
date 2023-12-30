@if ($errors->count() > 0)
	@vite('resources/css/errors.css')
@endif

@if ($errors->count() == 1)
	<div id="errors">
		A validation error occurred. Please check the form and try again.
	</div>
@elseif ($errors->count() > 1)
	<div id="errors">
		Validation errors occurred. Please check the form and try again.
	</div>
@endif
