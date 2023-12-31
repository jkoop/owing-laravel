<ul>
	@foreach ($users as $user)
		@php($owing = $user->getOwing(Auth::user()))
		@if ($owing > 0)
			<li>You owe <x-user :user="$user" /> ${{ number_format($owing, 4) }}</li>
		@elseif ($owing == 0)
			@continue ($user->deleted_at != null)
			<li><x-user :user="$user" /> and you are even</li>
		@else
			<li><x-user :user="$user" /> owes you ${{ number_format(-$owing, 4) }}</li>
		@endif
	@endforeach
</ul>
