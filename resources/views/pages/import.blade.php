@extends('layouts.default')
@section('title', 'Import from DriveTrak')
@section('content')

	<div class="warning">⚠ This import will <i>completely, irreversibly</i> overwrite your current database!</div>

	<p>The first user imported will be made an admin.</p>

	<form method="post" enctype='multipart/form-data' onsubmit="return confirm('Really, really? Clobber the current DB?')">
		@csrf
		<input name="file" type="file" accept="*.drivetrak" required /><br>
		<button>Import</button>
	</form>

@endsection
