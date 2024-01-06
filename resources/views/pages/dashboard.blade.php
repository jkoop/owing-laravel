@extends('layouts.default')
@section('title', t('Dashboard'))
@section('content')

	<livewire:owing-totals />

	{{ App::currentLocale() }}

	<h2>@t('Ledger')</h2>

	<nav>
		<a href="/t/new">@t('New')</a>
		@if (request()->has('deleted'))
			<a href="/">@t('Hide deleted')</a>
		@else
			<a href="/?deleted">@t('Show deleted')</a>
		@endif
	</nav>

	<table>
		<thead>
			<tr>
				<th>@t('Occurred at')</th>
				<th>@t('From')</th>
				<th>@t('To')</th>
				<th>@t('Amount')</th>
				<th>@t('Car')</th>
				<th>@t('Memo')</th>
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
							<i>@t('no memo')</i>
						@endif
					</td>
					<td>
						<a class="inline-block" href="/t/{{ $transaction->id }}">
							@can('update', $transaction)
								@t('edit')
							@else
								@t('view')
							@endcan
						</a>
						<a href="/t/new?clone={{ $transaction->id }}">@t('clone')</a>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>

@endsection
