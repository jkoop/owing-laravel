@extends('layouts.default')
@section('title', $car->id == null ? t('New Car') : t(':name - Car', ['name' => $car->name]))
@section('content')

	@can('update', $car)
		<form method="post">
			@csrf
			<table>
				<tr>
					<td>@t('Name')</td>
					<td><x-input name="name" :value="$car->name" :autofocus="$car->id == null" required /></td>
				</tr>
				<tr>
					<td>@t('Efficiency')</td>
					<td>
						<x-input name="efficiency" type="number" min="0.0001" max="10" step="0.0001" :value="$car->efficiency?->efficiency" required />
						L/km;
						<x-dollar-efficiency :car="$car" with-time />
					</td>
				</tr>
				<tr>
					<td>@t('Fuel Type')</td>
					<td><x-select name="fuel_type" :selected="$car->fuelType?->fuel_type" required>
							<option></option>
							@foreach (App\Models\CarFuelType::FUEL_TYPES as $fuelTypeId => $fuelType)
								<x-select.option :value="$fuelTypeId">@t($fuelType)</x-select.option>
							@endforeach
						</x-select></td>
				</tr>
				<tr>
					<td>@t('Owner')</td>
					<td><x-select name="owner_id" :selected="$car->id == null ? Auth::id() : $car->owner_id" :disabled="!Auth::user()->is_admin">
							<option value="">@t('(nobody)')</option>
							@foreach (App\Models\User::withTrashed()->orderBy('name')->get() as $user)
								<x-select.option :value="$user->id">{{ $user->name }}</x-select.option>
							@endforeach
						</x-select></td>
				</tr>
			</table>

			<button>@t('Save')</button>

			{{-- if we're the owner --}}
			@if (Auth::id() == $car->owner_id)
				@if ($car->deleted_at == null)
					<button name="delete" value="on">@t('Delete')</button>
				@else
					<button name="restore" value="on">@t('Restore')</button>
					@t('Deleted') <x-datetime :datetime="$car->deleted_at" relative />
				@endif
			@endif
		</form>
	@else
		<table>
			<tr>
				<th>@t('Name')</th>
				<td>{{ $car->name }}</td>
			</tr>
			<tr>
				<th>@t('Efficiency')</th>
				<td>{{ number_format($car->efficiency->efficiency, 4) }} L/km; <x-dollar-efficiency :car="$car" with-time />
				</td>
			</tr>
			<tr>
				<th>@t('Fuel Type')</th>
				<td>@t(App\Models\CarFuelType::FUEL_TYPES[$car->fuelType->fuel_type])</td>
			</tr>
			<tr>
				<th>@t('Owner')</th>
				<td><x-user :user="$car->owner" /></td>
			</tr>
		</table>

		@if ($car->deleted_at != null)
			@t('Deleted :datetime', ['datetime' => c('datetime', ['datetime' => $car->deleted_at, 'relative' => true])])
		@endif
	@endcan

	@if ($car->id)
		<livewire:change-history :model="$car" />
	@endif

@endsection
