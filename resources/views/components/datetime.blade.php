@props(['datetime', 'relative' => false])

@if ($datetime == null)
	<i>none</i>
@elseif ($relative)
	@once
		@vite('resources/js/datetime-relative.js')
	@endonce

	<time class="datetime-relative" datetime="{{ $datetime->format('r') }}">{{ $datetime }}</time>
@else
	@once
		@vite('resources/js/datetime-absolute.js')
	@endonce

	<time class="datetime-absolute" datetime="{{ $datetime->format('r') }}">{{ $datetime }}</time>
@endif
