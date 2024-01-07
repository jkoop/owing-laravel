<div>
	@once
		@vite('resources/css/change-history.css')
	@endonce

	<h2>@t('Change history') <x-spinner /></h2>

	<table class="change-history">
		<thead>
			<tr>
				<th>@t('Date')</th>
				<th>@t('Author')</th>
				<th>@t('Description')</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td colspan="3"><x-spinner /></td>
			</tr>
		</tbody>
	</table>
</div>
