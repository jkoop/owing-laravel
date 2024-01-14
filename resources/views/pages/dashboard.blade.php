@extends('layouts.default')
@section('title', t('Dashboard'))
@section('content')

	<livewire:owing-totals />

	<h2>@t('Ledger') <a class="text-base decoration-dotted" href="https://github.com/jkoop/owing-laravel/wiki/Ledger"
			target="_blank">(?)</a></h2>

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
			<tr x-data="{{ json_encode([
			    'user_id' => request()->user_id,
			    'disabled' => false,
			]) }}">
				<th>@t('Occurred at')</th>
				<th>
					@t('User')
					<button x-show="user_id===null" @click="user_id=-1">=</button>
					<x-select name="user_id" x-show="user_id!==null" onchange="location.href=`?user_id=${this.value}`" x-cloak>
						<option></option>
						@foreach ($users as $user)
							<x-select.option value="{{ $user->id }}">{{ $user->name }}</x-select.option>
						@endforeach
					</x-select>
					<x-spinner x-show="false" />
				</th>
				<th>@t('Credit')</th>
				<th>@t('Car')</th>
				<th>@t('Memo')</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			@foreach ($transactions as $transaction)
				<tr>
					<td><x-datetime :datetime="$transaction->occurred_at" /></td>
					<td><x-user :user="$transaction->otherUser" /></td>
					<td>
						@if ($transaction->deleted_at !== null)
							<del>${{ number_format($transaction->credit, 2) }}</del>
						@else
							${{ number_format($transaction->credit, 2) }}
						@endif
					</td>
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
