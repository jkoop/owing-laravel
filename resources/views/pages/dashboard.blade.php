@extends('layouts.default')
@section('title', 'Dashboard')
@section('content')

	<livewire:owing-totals lazy />

	<h2>Ledger</h2>

	<nav>
		<a href="/transaction/new">New</a>
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
					<td>{{ $transaction->amount }}</td>
					<td>{{ $transaction->memo }}</td>
					<td><a href="/transaction/{{ $transaction->id }}">edit</a></td>
				</tr>
			@endforeach
		</tbody>
	</table>

@endsection
