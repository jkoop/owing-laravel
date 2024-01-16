@extends('layouts.default')
@section('title', t('Cars'))
@section('content')

	<nav class="p-2 bg-blue-100 mb-4 flex flex-row flex-wrap gap-4">
		@if (request()->has('deleted'))
			<a href="/c">@t('Hide deleted')</a>
		@else
			<a href="/c?deleted">@t('Show deleted')</a>
		@endif
		<a class="ml-auto" href="/c/new">@t('New')</a>
	</nav>

	<table>
		<thead>
			<tr>
				<th>@t('Name')</th>
				<th>@t('Efficiency')</th>
				<th>@t('Fuel Type')</th>
				<th>@t('Owner')</th>
				@if (request()->has('deleted'))
					<th>@t('Deleted')</th>
				@endif
			</tr>
		</thead>
		<tbody>
			@foreach ($cars->sortBy('name') as $car)
				<tr>
					<td><x-car :car="$car" /></td>
					<td>{{ number_format($car->efficiency->efficiency, 4) }}L/km; <x-dollar-efficiency :car="$car" /></td>
					<td>@t(App\Models\CarFuelType::FUEL_TYPES[$car->fuelType->fuel_type])</td>
					<td><x-user :user="$car->owner" /></td>
					@if (request()->has('deleted'))
						<td><x-datetime :datetime="$car->deleted_at" relative /></td>
					@endif
				</tr>
			@endforeach
			@if ($cars->count() < 1)
				<tr>
					<td colspan="3"><i>@t('no cars')</i></td>
				</tr>
			@endif
		</tbody>
	</table>

@endsection
