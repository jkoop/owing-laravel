@extends('layouts.default')
@section('title', 'Change Password')
@section('content')

	<p class="warning">System policy requires you to change your password before continuing.</p>

	<form method="post">
		@csrf
		<table>
			<tr>
				<td>New password</td>
				<td><x-input name="password" type="password" minlength="8" autofocus required /></td>
				<td></td>
			</tr>
		</table>

		<button>Save</button>
	</form>

@endsection
