@props(['car'])

@if (!$car instanceof App\Models\Car)
	<i>no car</i>
@else
	<a href="/car/{{ $car->id }}" @if ($car->deleted_at != null) class="deleted" @endif>{{ $car->name }}</a>
@endif
