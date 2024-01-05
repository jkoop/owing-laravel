@extends('layouts.default')
@section('title', $car->id == null ? 'New Car' : $car->name . ' - Car')
@section('content')

	@can('update', $car)
		<form method="post">
			@csrf
			<table>
				<tr>
					<td>Name</td>
					<td><x-input name="name" :value="$car->name" :autofocus="$car->id == null" required /></td>
				</tr>
				<tr>
					<td>Efficiency</td>
					<td>
						<x-input name="efficiency" type="number" min="0.0001" max="10" step="0.0001" :value="$car->efficiency?->efficiency" required />
						L/km;
						<x-dollar-efficiency :car="$car" with-time />
					</td>
				</tr>
				<tr>
					<td>Fuel Type</td>
					<td><x-select name="fuel_type" :selected="$car->fuelType?->fuel_type" required>
							<option></option>
							@foreach (App\Models\CarFuelType::FUEL_TYPES as $fuelTypeId => $fuelType)
								<x-select.option :value="$fuelTypeId">{{ $fuelType }}</x-select.option>
							@endforeach
						</x-select></td>
				</tr>
				<tr>
					<td>Owner</td>
					<td><x-select name="owner_id" :selected="$car->id == null ? Auth::id() : $car->owner_id" :disabled="!Auth::user()->is_admin">
							<option value="">(nobody)</option>
							@foreach (App\Models\User::withTrashed()->orderBy('name')->get() as $user)
								<x-select.option :value="$user->id">{{ $user->name }}</x-select.option>
							@endforeach
						</x-select></td>
				</tr>
			</table>

			<button>Save</button>

			{{-- if we're the owner --}}
			@if (Auth::id() == $car->owner_id)
				@if ($car->deleted_at == null)
					<button name="delete" value="on">Delete</button>
				@else
					<button name="restore" value="on">Restore</button>
					Deleted <x-datetime :datetime="$car->deleted_at" relative />
				@endif
			@endif
		</form>
	@else
		<table>
			<tr>
				<th>Name</th>
				<td>{{ $car->name }}</td>
			</tr>
			<tr>
				<th>Efficiency</th>
				<td>{{ number_format($car->efficiency->efficiency, 4) }} L/km; <x-dollar-efficiency :car="$car" with-time />
				</td>
			</tr>
			<tr>
				<th>Fuel Type</th>
				<td>{{ App\Models\CarFuelType::FUEL_TYPES[$car->fuelType->fuel_type] }}</td>
			</tr>
			<tr>
				<th>Owner</th>
				<td><x-user :user="$car->owner" /></td>
			</tr>
		</table>

		@if ($car->deleted_at != null)
			Deleted <x-datetime :datetime="$car->deleted_at" relative />
		@endif
	@endcan

	@if ($car->id)
		<livewire:change-history :model="$car" />
	@endif

@endsection
