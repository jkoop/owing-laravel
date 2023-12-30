@props([
    'checked' => false,
    'name',
    'type' => 'text',
    'value' => null,
])

@once
	@vite('resources/css/input.css')
@endonce

{{-- prettier-ignore --}}
<input
type="{{ $type }}"
name="{{ $name }}"
x-model="{{ $name }}"
@if (old($name, $value) !== null)
	value="{{ $type != 'checkbox' && $type != 'radio' ? old($name, $value) : $value }}"
@endif
@checked($type == 'checkbox' && (old($name) !== null || $checked))
@checked($type == 'radio' && (old($name) === null && $checked))
@checked($type == 'radio' && (old($name) !== null && old($name) == $value))
{{ $attributes }}
@if (!empty($errors->get($name)) > 0)
	autofocus
	invalid
@endif
/>

@if (!empty($errors->get($name)) > 0)
	<br>
	<div class="validation-errors">
		@foreach ($errors->get($name) as $error)
			{{ $error }}<br>
		@endforeach
	</div>
@endif

{{--
	$type == "checkbox"

	old($name) / $checked   |   true    |   false   |
	-------------------------------------------------
	null                    |   checked |   -       |
	"on"                    |   checked |   checked |
--}}

{{--
	$type == "radio"

	old($name) / $checked   |   true    |   false   |
	-------------------------------------------------
	null                    |   checked |   -       |
	!== null && == $value   |   checked |   checked |
	!== null && != $value   |   -       |   -       |
--}}
