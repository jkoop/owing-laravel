@extends('layouts.default')
@section('title', $transaction->id == null ? 'New Transaction' : 'Transaction #' . $transaction->id)
@section('content')

@php($data = [
	'kind' => 0,
	'car_id' => 0,
	'from_to' => 0,
	'other_user_id' => 0,
	'amount' => 0,
	'distance' => 0,
	'occurred_at' => 0,
	'memo' => '',
	'my_cars' => Auth::user()->cars->pluck('id')->toArray(),
])

<script>
	console.log(@json($data))
</script>

	<form method="post" x-data="{{ json_encode($data) }}">
		@csrf

		<fieldset>
			<legend>What kind of transaction is this?</legend>
			<label><x-input name="kind" type="radio" value="owing" :checked="$transaction->kind == 'owing'" /> Someone owes money</label><br>
			<label><x-input name="kind" type="radio" value="payment" :checked="$transaction->kind == 'payment'" /> Someone payed money</label><br>
			<label><x-input name="kind" type="radio" value="drivetrak" :checked="$transaction->kind == 'drivetrak'" /> Someone drove a car</label>
		</fieldset>

		<fieldset x-cloak x-show="kind && kind != 'drivetrak'" x-transition>
			<legend>Which way is money going?</legend>
			<label>
				<x-input name="from_to" type="radio" value="from" />
				<span x-show="kind == 'owing'">I owe him</span>
				<span x-show="kind != 'owing'">I payed him</span>
			</label><br>
			<label>
				<x-input name="from_to" type="radio" value="to" />
				<span x-show="kind == 'owing'">He owes me</span>
				<span x-show="kind != 'owing'">He payed me</span>
			</label>
		</fieldset>

		<fieldset x-cloak x-show="kind == 'drivetrak'" x-transition>
			<legend>Which car?</legend>
			<x-select name="car_id" :selected="$transaction->car_id">
				<option></option>
				@foreach (App\Models\Car::all() as $car)
					<x-select.option :value="$car->id">{{ $car->name }}</x-select.option>
				@endforeach
			</x-select>
		</fieldset>

		<fieldset x-cloak x-show="(kind && kind != 'drivetrak' && from_to) || (kind == 'drivetrak' && my_cars.includes(parseInt(car_id)))" x-transition>
			<legend x-show="kind && from_to">Who is he?</legend>
			<legend x-show="kind == 'drivetrak' && my_cars.includes(parseInt(car_id))">Who drove it?</legend>

			<x-select name="other_user_id" :selected="$transaction->other_user_id">
				<option></option>
				@foreach (App\Models\User::whereNot('id', Auth::id())->orderBy('name')->get() as $user)
					<x-select.option :value="$user->id">{{ $user->name }}</x-select.option>
				@endforeach
			</x-select>
		</fieldset>

		<fieldset x-cloak x-show="kind == 'drivetrak' && car_id && (!my_cars.includes(parseInt(car_id)) || my_cars.includes(parseInt(car_id)) && other_user_id)" x-transition>
			<legend>How far?</legend>
			<x-input name="distance" type="number" step="0.0001" style="max-width:70px" min="0" :value="$transaction->distance" />km
		</fieldset>

		<fieldset x-cloak x-show="kind && kind != 'drivetrak' && from_to && other_user_id" x-transition>
			<legend x-show="kind == 'owing' && from_to == 'from'">How much do you owe him?</legend>
			<legend x-show="kind == 'owing' && from_to == 'to'">How much does he owe you?</legend>
			<legend x-show="kind != 'owing' && from_to == 'from'">How much did you pay him?</legend>
			<legend x-show="kind != 'owing' && from_to == 'to'">How much did he pay you?</legend>

			$<x-input name="amount" type="number" step="0.0001" style="max-width:70px" min="0" :value="$transaction->amount" />
		</fieldset>

		<fieldset x-cloak x-show="(kind && kind != 'drivetrak' && from_to && other_user_id && amount) || (kind == 'drivetrak' && car_id && (!my_cars.includes(parseInt(car_id)) || my_cars.includes(parseInt(car_id)) && other_user_id) && distance)" x-transition>
			<legend x-show="kind && kind != 'drivetrak' && from_to && other_user_id && amount">When should this be considered to have happened / will happen?</legend>
			<legend x-show="kind == 'drivetrak' && car_id && my_cars.includes(parseInt(car_id)) && other_user_id && distance">When did/will he drive it?</legend>
			<legend x-show="kind == 'drivetrak' && car_id && !my_cars.includes(parseInt(car_id)) && distance">When did/will you drive it?</legend>
			<x-input name="occurred_at" type="date" :value="$transaction->occurred_at" @todo />
		</fieldset>

		<fieldset x-cloak x-show="(kind && kind != 'drivetrak' && from_to && other_user_id && amount && occurred_at) || (kind == 'drivetrak' && car_id && (!my_cars.includes(parseInt(car_id)) || my_cars.includes(parseInt(car_id)) && other_user_id) && distance && occurred_at)" x-transition>
			<legend>Optional memo</legend>
			<x-input type="text" name="memo" :value="$transaction->memo" placeholder="It's optional" />
		</fieldset>

		<button x-cloak x-show="(kind && kind != 'drivetrak' && from_to && other_user_id && amount && occurred_at) || (kind == 'drivetrak' && car_id && (!my_cars.includes(parseInt(car_id)) || my_cars.includes(parseInt(car_id)) && other_user_id) && distance && occurred_at)" x-transition>Save</button>
		<span x-cloak x-show="kind == 'drivetrak' && car_id && (!my_cars.includes(parseInt(car_id)) || my_cars.includes(parseInt(car_id)) && other_user_id) && distance" x-transition>Aprox amount: <span x-text="axios.get(`/calculate/trip-price?car_id=${car_id}&distance=${distance}&date=${occurred_at}`).then(r => r.data).catch(e => e.code)"></span></span>

		@if ($transaction->id)
			@if ($transaction->deleted_at == null)
				<button name="delete" value="on">Delete</button>
			@else
				<button name="restore" value="on">Restore</button>
				Deleted <x-datetime :datetime="$transaction->deleted_at" relative />
			@endif
		@endif
	</form>

	@if ($transaction->id)
		@include('blocks.change-history', ['model' => $transaction])
	@endif

@endsection
