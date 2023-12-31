@props(['name', 'selected' => null])

<select name="{{ $name }}" x-model="{{ $name }}" {{ $attributes }}>
	{{ $slot }}
</select>
