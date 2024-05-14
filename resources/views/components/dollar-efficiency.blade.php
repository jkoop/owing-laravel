@props(['car', 'withTime' => false])

@if ($car->fuelType?->fuel_type != null)
	@php($fuelPrice = FuelPriceRepository::getFuelPrice($car->fuelType->fuel_type))

	@if ($withTime)
		@t(':moneyPerDistance as of :date', [
		    'moneyPerDistance' => '$' . number_format($fuelPrice->price * $car->efficiency->efficiency, 2) . '/km',
		    'date' => c('datetime', ['datetime' => $fuelPrice->created_at, 'relative' => true]),
		])
	@else
		${{ number_format($fuelPrice->price * $car->efficiency->efficiency, 2) }}/km
	@endif
@endif
