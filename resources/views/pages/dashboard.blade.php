@extends('layouts.default')
@section('title', 'Dashboard')
@section('content')

	<livewire:owing-totals lazy />

	<h2>Ledger</h2>

	<nav>
		<a href="/t/new">New</a>
		@if (request()->has('deleted'))
			<a href="/">Hide deleted</a>
		@else
			<a href="/?deleted">Show deleted</a>
		@endif
	</nav>

	<table>
		<thead>
			<tr>
				<th>Occurred At</th>
				<th>From</th>
				<th>To</th>
				<th>Amount</th>
				<th>Car</th>
				<th>Memo</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			@foreach ($transactions as $transaction)
				<tr>
					<td><x-datetime :datetime="$transaction->occurred_at" /></td>
					<td><x-user :user="$transaction->userFrom" /></td>
					<td><x-user :user="$transaction->userTo" /></td>
					<td>${{ number_format($transaction->amount, 2) }}</td>
					<td><x-car :car="$transaction->car" /></td>
					<td>
						@if ($transaction->memo != null)
							{{ $transaction->memo }}
						@else
							<i>no memo</i>
						@endif
					</td>
					<td>
						<a href="/t/{{ $transaction->id }}">
							@can('update', $transaction)
								edit
							@else
								view
							@endcan
						</a>
						<a href="/t/new?clone={{ $transaction->id }}">clone</a>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>

@endsection
