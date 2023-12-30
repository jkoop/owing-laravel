@extends('layouts.default')
@section('title',
	$profile
	? 'Profile'
	: ($user->id == null
	? 'New User'
	: $user->name .
	' -
	User'))
@section('content')

	<form method="post">
		@csrf
		<table>
			<tr>
				<td>Username</td>
				<td><x-input name="username" :value="$user->username" :autofocus="$user->id == null" required /></td>
				<td>Not shown to others; used to log in</td>
			</tr>
			<tr>
				<td>Name</td>
				<td><x-input name="name" :value="$user->name" required /></td>
				<td>Shown to others</td>
			</tr>
			<tr>
				<td>New password</td>
				<td><x-input name="password" type="password" placeholder="leave blank to not change" minlength="8" /></td>
				<td></td>
			</tr>

			{{-- if we're admin and this is not the profile page --}}
			@if (Auth::user()->is_admin and !$profile)
				<tr>
					<td></td>
					<td><label><x-input name="must_change_password" type="checkbox" :checked="$user->must_change_password" /> Must change password</label>
					</td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td><label><x-input name="is_admin" type="checkbox" :checked="$user->is_admin" /> Is admin</label></td>
					<td></td>
				</tr>
			@endif
		</table>

		<button>Save</button>

		{{-- if we're admin and this is not the profile page --}}
		@if ($user->id and Auth::user()->is_admin and $user->id != Auth::id())
			@if ($user->deleted_at == null)
				<button name="delete" value="on">Delete</button>
			@else
				<button name="restore" value="on">Restore</button>
				Deleted <x-datetime :datetime="$user->deleted_at" relative />
			@endif
		@endif
	</form>

	@if ($user->id)
		@include('blocks.change-history', ['model' => $user])
	@endif

@endsection
