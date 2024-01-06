@props(['datetime', 'relative' => false])

@if ($datetime == null)
	<i>@t('none')</i>
@elseif ($relative)
	<time class="datetime-relative" datetime="{{ $datetime->format('r') }}"
		@once
x-init="if (typeof window.renderDatetimeRelative == 'function') window.renderDatetimeRelative()" @endonce>
		{{ $datetime }}
	</time>
@else
	<time class="datetime-absolute" datetime="{{ $datetime->format('r') }}"
		@once
x-init="if (typeof window.renderDatetimeAbsolute == 'function') window.renderDatetimeAbsolute()" @endonce>
		{{ $datetime }}
	</time>
@endif
