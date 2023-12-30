@props(['name', 'selected' => null])

<select x-model="{{ $name }}" name="{{ $name }}" {{ $attributes }}>
	{{ $slot }}
</select>
