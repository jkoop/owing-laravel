@props(['name', 'selected' => null])

<select name="{{ $name }}" x-bind:disabled="disabled == true" x-model="{{ $name }}" {{ $attributes }}>
	{{ $slot }}
</select>
