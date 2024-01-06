<ul>
	@foreach ($users as $user)
		@php($owing = $user->getOwing(Auth::user()))
		@if ($owing > 0)
			<li>
				@t('You owe :user :money', [
				    'user' => c('user', ['user' => $user]),
				    'money' => '$' . number_format($owing, 2),
				])
			</li>
		@elseif ($owing == 0)
			@continue ($user->deleted_at != null)
			<li>@t(':user and you are even', [
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
