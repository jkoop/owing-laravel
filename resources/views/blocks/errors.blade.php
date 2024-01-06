@if ($errors->count() > 0)
	@vite('resources/css/errors.css')
@endif

@if ($errors->count() == 1)
	<div id="errors">
		@t('A validation error occurred. Please check the form and try again.')
	</div>
@elseif ($errors->count() > 1)
	<div id="errors">
		@t('Validation errors occurred. Please check the form and try again.')
	</div>
@endif
