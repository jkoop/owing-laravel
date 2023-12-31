<ul>
	@foreach (App\Models\User::where('id', '!=', Auth::id())->orderBy('name')->get() as $user)
		<li><x-user :user="$user" /> <x-spinner /></li>
	@endforeach
</ul>
