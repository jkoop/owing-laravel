@extends('layouts.default')
@section('title', $transaction->id == null ? 'New Transaction' : 'Transaction #' . $transaction->id)
@section('content')

	{{-- prettier-ignore --}}
	@php($data = [
	'kind' => old('kind', $transaction->kind),
	'car_id' => old('car_id', $transaction->car_id),
	'from_to' => old('from_to', $transaction->from_user_id == Auth::id() ? 'from' : 'to'),
	'other_user_id' => old('other_user_id', $transaction->from_user_id == Auth::id() ? $transaction->to_user_id :
	$transaction->from_user_id),
	'amount' => old('amount', $transaction->amount),
	'distance' => old('distance', $transaction->distance),
	'occurred_at' => old('occurred_at', $transaction->occurred_at?->format('Y-m-d')),
	'memo' => old('memo', $transaction->memo),
	'my_cars' => Auth::user()->cars->pluck('id')->toArray(),
	'disabled' => !Auth::user()->can('update', $transaction),
	])

	<script>
		console.log(@json($data))
	</script>

	@can('update', $transaction)
		<form method="post" x-data="{{ json_encode($data) }}">
			@csrf
		@else
			<div x-data="{{ json_encode($data) }}">
			@endcan

			<fieldset>
				<legend>What kind of transaction is this?</legend>
				<label><x-input name="kind" type="radio" value="owing" /> Someone owes money</label>
				<label><x-input name="kind" type="radio" value="payment" /> Someone paid money</label>
				<label><x-input name="kind" type="radio" value="drivetrak" /> Someone drove a car</label>
			</fieldset>

			<fieldset x-cloak x-show="kind && kind != 'drivetrak'" x-transition :class="{ 'flex-col-reverse': kind != 'owing' }">
				<legend>Which way is money going?</legend>
				<label>
					<x-input name="from_to" type="radio" value="from" />
					<span x-show="kind == 'owing'">I owe him</span>
					<span x-show="kind != 'owing'">He paid me</span>
				</label>
				<label>
					<x-input name="from_to" type="radio" value="to" />
					<span x-show="kind == 'owing'">He owes me</span>
					<span x-show="kind != 'owing'">I paid him</span>
				</label>
			</fieldset>

			<fieldset x-cloak x-show="kind == 'drivetrak'" x-transition>
				<legend>Which car?</legend>
				<x-select name="car_id">
					<option></option>
					@foreach (App\Models\Car::all() as $car)
						<x-select.option :value="$car->id">{{ $car->name }}</x-select.option>
					@endforeach
				</x-select>
			</fieldset>

			<fieldset x-cloak
				x-show="(kind && kind != 'drivetrak' && from_to) || (kind == 'drivetrak' && my_cars.includes(parseInt(car_id)))"
				x-transition>
				<legend x-show="kind && kind != 'drivetrak' && from_to">Who is he?</legend>
				<legend x-show="kind == 'drivetrak' && my_cars.includes(parseInt(car_id))">Who drove it?</legend>

				<x-select name="other_user_id">
					<option></option>
					@foreach (App\Models\User::whereNot('id', Auth::id())->orderBy('name')->get() as $user)
						<x-select.option :value="$user->id">{{ $user->name }}</x-select.option>
					@endforeach
				</x-select>
			</fieldset>

			<fieldset x-cloak
				x-show="kind == 'drivetrak' && car_id && (!my_cars.includes(parseInt(car_id)) || my_cars.includes(parseInt(car_id)) && other_user_id)"
				x-transition>
				<legend>How far?</legend>
				<span><x-input name="distance" type="number" style="max-width:70px" step="0.01" min="0" />km</span>
			</fieldset>

			<fieldset x-cloak x-show="kind && kind != 'drivetrak' && from_to && other_user_id" x-transition>
				<legend x-show="kind == 'owing' && from_to == 'from'">How much do you owe him?</legend>
				<legend x-show="kind == 'owing' && from_to == 'to'">How much does he owe you?</legend>
				<legend x-show="kind != 'owing' && from_to == 'to'">How much did you pay him?</legend>
				<legend x-show="kind != 'owing' && from_to == 'from'">How much did he pay you?</legend>

				<span>$<x-input name="amount" type="number" style="max-width:70px" step="0.01" min="0" /></span>
			</fieldset>

			<fieldset x-cloak
				x-show="(kind && kind != 'drivetrak' && from_to && other_user_id && amount) || (kind == 'drivetrak' && car_id && (!my_cars.includes(parseInt(car_id)) || my_cars.includes(parseInt(car_id)) && other_user_id) && distance)"
				x-transition>
				<legend x-show="kind && kind != 'drivetrak' && from_to && other_user_id && amount">
					When should this be considered to have happened / will happen?
				</legend>
				<legend x-show="kind == 'drivetrak' && car_id && my_cars.includes(parseInt(car_id)) && other_user_id && distance">
					When did/will he drive it?
				</legend>
				<legend x-show="kind == 'drivetrak' && car_id && !my_cars.includes(parseInt(car_id)) && distance">
					When did/will you drive it?
				</legend>
				<x-input name="occurred_at" type="date" />
			</fieldset>

			<fieldset x-cloak
				x-show="(kind && kind != 'drivetrak' && from_to && other_user_id && amount && occurred_at) || (kind == 'drivetrak' && car_id && (!my_cars.includes(parseInt(car_id)) || my_cars.includes(parseInt(car_id)) && other_user_id) && distance && occurred_at)"
				x-transition>
				<legend>Optional memo</legend>
				<x-input name="memo" type="text" placeholder="It's optional" />
			</fieldset>

			@if ($transaction->id)
				<p x-cloak
					x-show="kind == 'drivetrak' && car_id && (!my_cars.includes(parseInt(car_id)) || my_cars.includes(parseInt(car_id)) && other_user_id) && distance"
					x-transition>Amount transacted: ${{ number_format($transaction->amount, 2) }}</p>
			@endif

			@can('update', $transaction)
				<p x-cloak
					x-show="kind == 'drivetrak' && car_id && (!my_cars.includes(parseInt(car_id)) || my_cars.includes(parseInt(car_id)) && other_user_id) && distance"
					x-transition>Aprox amount to transact: <span
						x-text="axios.get(`/calculate/trip-price?car_id=${car_id}&distance=${distance}&date=${occurred_at}`).then(r => r.data).catch(e => e.code)"></span>
					@if (!empty($errors->get('amount')) > 0)
						<br>
						<div class="validation-errors">
							@foreach ($errors->get('amount') as $error)
								{{ $error }}<br>
							@endforeach
						</div>
					@endif
				</p>

				<button x-cloak
					x-show="(kind && kind != 'drivetrak' && from_to && other_user_id && amount && occurred_at) || (kind == 'drivetrak' && car_id && (!my_cars.includes(parseInt(car_id)) || my_cars.includes(parseInt(car_id)) && other_user_id) && distance && occurred_at)"
					x-transition>Save</button>

				@if ($transaction->id)
					@if ($transaction->deleted_at == null)
						<button name="delete" value="on">Delete</button>
					@else
						<button name="restore" value="on">Restore</button>
						Deleted <x-datetime :datetime="$transaction->deleted_at" relative />
					@endif
				@endif
		</form>
	@else
		</div>
	@endcan

	@if ($transaction->id)
		<livewire:change-history :model="$transaction" lazy />
	@endif

@endsection
