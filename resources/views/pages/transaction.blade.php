@extends('layouts.default')
@section('title', $transaction->id == null ? t('New Transaction') : t('Transaction #:number', ['number' =>
	$transaction->id]))
@section('content')

	{{-- prettier-ignore --}}
	@php($data = [
	'kind' => old('kind', $transaction->kind),
	'car_id' => old('car_id', $transaction->car_id),
	'from_to' => old('from_to', $transaction->from_user_id == Auth::id() ? 'from' : 'to'),
	'other_user_id' => old('other_user_id', $transaction->otherUser?->id),
	'amount' => old('amount', $transaction->amount),
	'distance' => old('distance', $transaction->distance),
	'ratio' => old('ratio', $transaction->ratio ?? '1'),
	'occurred_at' => old('occurred_at', $transaction->occurred_at?->format('Y-m-d') ?? now()->format('Y-m-d')),
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
				<legend>@t('What kind of transaction is this?')</legend>
				<label><x-input name="kind" type="radio" value="owing" /> @t('Someone owes money')</label>
				<label><x-input name="kind" type="radio" value="payment" /> @t('Someone paid money')</label>
				<label><x-input name="kind" type="radio" value="drivetrak" /> @t('Someone drove a car')</label>
			</fieldset>

			<fieldset x-cloak x-show="kind && kind != 'drivetrak'" x-transition :class="{ 'flex-col-reverse': kind == 'owing' }">
				<legend>@t('Which way is money going?')</legend>
				<label>
					<x-input name="from_to" type="radio" value="from" />
					<span x-show="kind == 'owing'">@t('He owes me')</span>
					<span x-show="kind != 'owing'">@t('I paid him')</span>
				</label>
				<label>
					<x-input name="from_to" type="radio" value="to" />
					<span x-show="kind == 'owing'">@t('I owe him')</span>
					<span x-show="kind != 'owing'">@t('He paid me')</span>
				</label>
			</fieldset>

			<fieldset x-cloak x-show="kind == 'drivetrak'" x-transition>
				<legend>@t('Which car?')</legend>
				<x-select name="car_id">
					<option></option>
					@foreach (App\Models\Car::all()->sortBy("name") as $car)
						<x-select.option :value="$car->id">{{ $car->name }}</x-select.option>
					@endforeach
				</x-select>
			</fieldset>

			<fieldset x-cloak
				x-show="(kind && kind != 'drivetrak' && from_to) || (kind == 'drivetrak' && my_cars.includes(parseInt(car_id)))"
				x-transition>
				<legend x-show="kind && kind != 'drivetrak' && from_to">@t('Who is he?')</legend>
				<legend x-show="kind == 'drivetrak' && my_cars.includes(parseInt(car_id))">@t('Who drove it?')</legend>

				<x-select name="other_user_id">
					<option></option>
					@foreach ($users as $user)
						<x-select.option :value="$user->id">{{ $user->name }}</x-select.option>
					@endforeach
				</x-select>
			</fieldset>

			<fieldset x-cloak
				x-show="kind == 'drivetrak' && car_id && (!my_cars.includes(parseInt(car_id)) || my_cars.includes(parseInt(car_id)) && other_user_id)"
				x-transition>
				<legend>@t('How far?')</legend>
				<span><x-input name="distance" type="number" style="max-width:70px" step="0.01" min="0" />km</span>
				<label>@t('Ratio:') <x-select name="ratio">
						<option value="{{ 1 / 1 }}">1/1</option>
						<option value="{{ 7 / 8 }}">7/8</option>
						<option value="{{ 5 / 6 }}">5/6</option>
						<option value="{{ 4 / 5 }}">4/5</option>
						<option value="{{ 3 / 4 }}">3/4</option>
						<option value="{{ 2 / 3 }}">2/3</option>
						<option value="{{ 5 / 8 }}">5/8</option>
						<option value="{{ 3 / 5 }}">3/5</option>
						<option value="{{ 1 / 2 }}">1/2</option>
						<option value="{{ 2 / 5 }}">2/5</option>
						<option value="{{ 3 / 8 }}">3/8</option>
						<option value="{{ 1 / 3 }}">1/3</option>
						<option value="{{ 1 / 4 }}">1/4</option>
						<option value="{{ 1 / 5 }}">1/5</option>
						<option value="{{ 1 / 6 }}">1/6</option>
						<option value="{{ 1 / 8 }}">1/8</option>
					</x-select></label>
			</fieldset>

			<fieldset x-cloak x-show="kind && kind != 'drivetrak' && from_to && other_user_id" x-transition>
				<legend x-show="kind == 'owing' && from_to == 'to'">@t('How much do you owe him?')</legend>
				<legend x-show="kind == 'owing' && from_to == 'from'">@t('How much does he owe you?')</legend>
				<legend x-show="kind != 'owing' && from_to == 'from'">@t('How much did you pay him?')</legend>
				<legend x-show="kind != 'owing' && from_to == 'to'">@t('How much did he pay you?')</legend>

				<span>$<x-input name="amount" type="number" style="max-width:70px" step="0.01" min="0" /></span>
			</fieldset>

			<fieldset x-cloak
				x-show="(kind && kind != 'drivetrak' && from_to && other_user_id && amount) || (kind == 'drivetrak' && car_id && (!my_cars.includes(parseInt(car_id)) || my_cars.includes(parseInt(car_id)) && other_user_id) && distance)"
				x-transition>
				<legend x-show="kind && kind != 'drivetrak' && from_to && other_user_id && amount">
					@t('When should this be considered to have happened / will happen?')
				</legend>
				<legend x-show="kind == 'drivetrak' && car_id && my_cars.includes(parseInt(car_id)) && other_user_id && distance">
					@t('When did/will he drive it?')
				</legend>
				<legend x-show="kind == 'drivetrak' && car_id && !my_cars.includes(parseInt(car_id)) && distance">
					@t('When did/will you drive it?')
				</legend>
				<x-input name="occurred_at" type="date" required />
			</fieldset>

			<fieldset x-cloak
				x-show="(kind && kind != 'drivetrak' && from_to && other_user_id && amount && occurred_at) || (kind == 'drivetrak' && car_id && (!my_cars.includes(parseInt(car_id)) || my_cars.includes(parseInt(car_id)) && other_user_id) && distance && occurred_at)"
				x-transition>
				<legend>@t('Optional memo')</legend>
				<x-input name="memo" type="text" :placeholder="t('It\'s optional')" />
			</fieldset>

			@if ($transaction->id)
				<p x-cloak
					x-show="kind == 'drivetrak' && car_id && (!my_cars.includes(parseInt(car_id)) || my_cars.includes(parseInt(car_id)) && other_user_id) && distance"
					x-transition>@t('Amount transacted: :amount', ['amount' => '$' . number_format($transaction->amount, 2)])</p>
			@endif

			@can('update', $transaction)
				<p x-cloak
					x-show="kind == 'drivetrak' && car_id && (!my_cars.includes(parseInt(car_id)) || my_cars.includes(parseInt(car_id)) && other_user_id) && distance"
					x-transition>@t('Aprox amount to transact:') <span
						x-text="axios.get(`/calculate/trip-price?car_id=${car_id}&distance=${distance}&ratio=${ratio}&date=${occurred_at}`).then(r => r.data).catch(e => e.code)"></span>
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
					x-transition>@t('Save')</button>

				@if ($transaction->id)
					@if ($transaction->deleted_at == null)
						<button name="delete" value="on">@t('Delete')</button>
					@else
						<button name="restore" value="on">@t('Restore')</button>
						@t('Deleted') <x-datetime :datetime="$transaction->deleted_at" relative />
					@endif
				@endif
		</form>
	@else
		</div>
	@endcan

	@if ($transaction->id)
		<livewire:change-history :model="$transaction" />
	@endif

@endsection
