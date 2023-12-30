@props(['car', 'withTime' => false])

@if ($car->fuel_type != null)
	@php($fuelPrice = FuelPriceRepository::getFuelPrice($car->fuel_type))

	${{ number_format($fuelPrice->price * $car->efficiency, 4) }}/km
	@if ($withTime)
		as of
		<x-datetime :datetime="$fuelPrice->created_at" relative />
	@endif
@endif
