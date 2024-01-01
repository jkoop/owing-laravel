@props([
    'noName' => 'nobody',
    'user',
])

@if (!$user instanceof App\Models\User)
	<i>{{ $noName }}</i>
@elseif (Auth::user()->is_admin)
	<a href="/u/{{ $user->id }}" @if ($user->deleted_at != null) class="deleted" @endif>{{ $user->name }}</a>
@else
	<span @if ($user->deleted_at != null) class="deleted" @endif>{{ $user->name }}</span>
@endif
