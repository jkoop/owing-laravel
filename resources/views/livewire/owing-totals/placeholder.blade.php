<ul>
	@foreach (App\Models\User::where('id', '!=', Auth::id())->orderBy('name')->get() as $user)
		<li>You owe <x-user :user="$user" /> <x-spinner /></li>
	@endforeach
</ul>
