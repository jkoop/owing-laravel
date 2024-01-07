@extends('layouts.default')
@section('title', t('Import from DriveTrak'))
@section('content')

	<div class="warning">@t('⚠ This import will <i>completely, irreversibly</i> overwrite your current database!')</div>

	<p>@t('The first user imported will be made an admin.')</p>

	<form method="post" enctype='multipart/form-data'
		onsubmit="return confirm({{ json_encode(t('Really, really? Clobber the current DB?')) }})">
		@csrf
		<input name="file" type="file" accept="*.drivetrak" required /><br>
		<button>@t('Import')</button>
	</form>

@endsection
