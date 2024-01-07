@extends('layouts.default')
@section('title', t('Users'))
@section('content')

	<nav>
		<a href="/u/new">@t('New')</a>
		@if (request()->has('deleted'))
			<a href="/u">@t('Hide deleted')</a>
		@else
			<a href="/u?deleted">@t('Show deleted')</a>
		@endif
	</nav>

	<table>
		<thead>
			<tr>
				<th>@t('Name')</th>
				<th>@t('Username')</th>
				<th>@t('Balance')</th>
				<th>@t('Last Transaction At')</th>
				<th>@t('Is Admin?')</th>
				@if (request()->has('deleted'))
					<th>@t('Deleted')</th>
				@endif
			</tr>
		</thead>
		<tbody>
			@foreach ($users->sortBy('name') as $user)
				<tr>
					<td><x-user :user="$user" /></td>
					<td>{{ $user->username }}</td>
					<td>${{ number_format($user->balance, 2) }}</td>
					<td><x-datetime :datetime="$user
					    ->transactions()
					    ->orderByDesc('occurred_at')
					    ->first()?->occurred_at" relative /></td>
					<td>{{ $user->is_admin ? 'true' : 'false' }}</td>
					@if (request()->has('deleted'))
						<td><x-datetime :datetime="$user->deleted_at" relative /></td>
					@endif
				</tr>
			@endforeach
			@if ($users->count() < 1)
				<tr>
					<td colspan="3"><i>@t('no users')</i></td>
				</tr>
			@endif
		</tbody>
	</table>

@endsection
