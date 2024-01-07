<div>
	@once
		@vite('resources/css/change-history.css')
	@endonce

	<h2>@t('Change history')</h2>

	<table class="change-history">
		<thead>
			<tr>
				<th>@t('Date')</th>
				<th>@t('Author')</th>
				<th>@t('Description')</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($changes as $change)
				<tr>
					<td><x-datetime :datetime="$change->created_at" /></td>
					<td><x-user :user="$change->author" :noName="t('system')" /></td>
					<td>{{ $change->description }}</td>
				</tr>
			@endforeach
		</tbody>
	</table>
</div>
