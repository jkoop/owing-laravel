<ul>
	@foreach ($users as $user)
		@php($owing = Auth::user()->getOwing($user))
		@if ($owing > 0)
			<li class="w-fit bg-red-200">
				@t('You owe :user :money', [
				    'user' => c('user', ['user' => $user]),
				    'money' => '$' . number_format($owing, 2),
				])
			</li>
		@elseif ($owing == 0)
			@continue ($user->deleted_at != null)
			<li class="opacity-50">@t(':user and you are even', [
			    'user' => c('user', ['user' => $user]),
			])</li>
		@else
			<li>
				@t(':user owes you :money', [
				    'user' => c('user', ['user' => $user]),
				    'money' => '$' . number_format(-$owing, 2),
				])
			</li>
		@endif
	@endforeach
</ul>
