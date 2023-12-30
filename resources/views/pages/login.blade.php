@extends('layouts.default')
@section('title', 'Login')
@section('content')

	<form method="post">
		@csrf
		<table>
			<tr>
				<td>Username</td>
				<td><x-input name="username" autofocus required /></td>
			</tr>
			<tr>
				<td>Password</td>
				<td><x-input name="password" type="password" required /></td>
			</tr>
			<tr>
				<td></td>
				<td><label><x-input name="remember_me" type="checkbox" checked /> Remember me</label></td>
			</tr>
		</table>
		<button>Login</button>
	</form>

@endsection
