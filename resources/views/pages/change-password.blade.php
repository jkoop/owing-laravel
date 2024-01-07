@extends('layouts.default')
@section('title', t('Change Password'))
@section('content')

	<p class="warning">@t('System policy requires you to change your password before continuing.')</p>

	<form method="post">
		@csrf
		<table>
			<tr>
				<td>@t('New password')</td>
				<td><x-input name="password" type="password" minlength="8" autofocus required /></td>
				<td></td>
			</tr>
		</table>

		<button>@t('Save')</button>
	</form>

@endsection
