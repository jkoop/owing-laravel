@extends('layouts.default')
@section('title', t('Login'))
@section('content')

	<form method="post">
		@csrf
		<table>
			<tr>
				<td>@t('Username')</td>
				<td><x-input name="username" autofocus required /></td>
			</tr>
			<tr>
				<td>@t('Password')</td>
				<td><x-input name="password" type="password" required /></td>
			</tr>
			<tr>
				<td></td>
				<td><label><x-input name="remember_me" type="checkbox" checked /> @t('Remember me')</label></td>
			</tr>
		</table>
		<button>@t('Login')</button>
	</form>

@endsection
