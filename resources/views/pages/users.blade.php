@extends('layouts.default')
@section('title', t('Users'))
@section('content')

	<nav class="p-2 bg-blue-100 mb-4 flex flex-row flex-wrap gap-4">
		@if (request()->has('deleted'))
			<a href="/u">@t('Hide deleted')</a>
		@else
			<a href="/u?deleted">@t('Show deleted')</a>
		@endif
		<a class="ml-auto" href="/u/new">@t('New')</a>
	</nav>

	<table>
		<thead>
			<tr>
				<th>@t('Name')</th>
				<th>@t('Username')</th>
				<th>@t('Balance')</th>
				<th>@t('Last Transaction')</th>
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
					<td>@t($user->is_admin ? 'true' : 'false')</td>
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
