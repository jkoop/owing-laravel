@aware(['selected', 'name'])

@props(['value'])

<option value="{{ $value }}" {{ $attributes }} @selected(old($name, $selected) == $value)>
	{{ $slot }}
</option>
