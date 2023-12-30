@extends('layouts.default')
@section('title', 'Users')
@section('content')

	<nav>
		<a href="/user/new">New</a>
		@if (request()->has('deleted'))
			<a href="/users">Hide deleted</a>
		@else
			<a href="/users?deleted">Show deleted</a>
		@endif
	</nav>

	<table>
		<thead>
			<tr>
				<th>Name</th>
				<th>Username</th>
				<th>Balance</th>
				<th>Last Transaction At</th>
				<th>Is Admin?</th>
				@if (request()->has('deleted'))
					<th>Deleted</th>
				@endif
			</tr>
		</thead>
		<tbody>
			@foreach ($users->sortBy('name') as $user)
				<tr>
					<td><x-user :user="$user" /></td>
					<td>{{ $user->username }}</td>
					<td>{{ $user->balance }}</td>
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
					<td colspan="3"><i>no users</i></td>
				</tr>
			@endif
		</tbody>
	</table>

@endsection
