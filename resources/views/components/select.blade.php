@props(['name', 'selected' => null])

<select name="{{ $name }}" x-bind:disabled="disabled == true" x-model="{{ $name }}" {{ $attributes }}>
	{{ $slot }}
</select>

@if (!empty($errors->get($name)) > 0)
	<br>
	<div class="validation-errors">
		@foreach ($errors->get($name) as $error)
			{{ $error }}<br>
		@endforeach
	</div>
@endif
