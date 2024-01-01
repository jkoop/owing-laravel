@extends('layouts.default')
@section('title', 'Cars')
@section('content')

	<nav>
		<a href="/c/new">New</a>
		@if (request()->has('deleted'))
			<a href="/c">Hide deleted</a>
		@else
			<a href="/c?deleted">Show deleted</a>
		@endif
	</nav>

	<table>
		<thead>
			<tr>
				<th>Name</th>
				<th>Efficiency</th>
				<th>Fuel Type</th>
				<th>Owner</th>
				@if (request()->has('deleted'))
					<th>Deleted</th>
				@endif
			</tr>
		</thead>
		<tbody>
			@foreach ($cars->sortBy('name') as $car)
				<tr>
					<td><x-car :car="$car" /></td>
					<td>{{ number_format($car->efficiency, 4) }}L/km; <x-dollar-efficiency :car="$car" /></td>
					<td>{{ $car->fuel_type }}</td>
					<td><x-user :user="$car->owner" /></td>
					@if (request()->has('deleted'))
						<td><x-datetime :datetime="$car->deleted_at" relative /></td>
					@endif
				</tr>
			@endforeach
			@if ($cars->count() < 1)
				<tr>
					<td colspan="3"><i>no cars</i></td>
				</tr>
			@endif
		</tbody>
	</table>

@endsection
