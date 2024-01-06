@props(['car'])

@if (!$car instanceof App\Models\Car)
	<i>@t('no car')</i>
@else
	<a href="/c/{{ $car->id }}" @if ($car->deleted_at != null) class="deleted" @endif>{{ $car->name }}</a>
@endif
