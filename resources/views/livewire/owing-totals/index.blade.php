<ul>
	@foreach ($users as $user)
		@php($owing = $user->getOwing(Auth::user()))
		@if ($owing > 0)
			<li>You owe <x-user :user="$user" /> {{ $owing }}</li>
		@elseif ($owing == 0)
			@continue ($user->deleted_at != null)
			<li><x-user :user="$user" /> and you are even</li>
		@else
			<li><x-user :user="$user" /> owes you {{ -$owing }}</li>
		@endif
	@endforeach
</ul>
