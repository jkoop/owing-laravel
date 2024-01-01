<div>
	@once
		@vite('resources/css/change-history.css')
	@endonce

	<h2>Change history <x-spinner /></h2>

	<table class="change-history">
		<thead>
			<tr>
				<th>Date</th>
				<th>Author</th>
				<th>Description</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td colspan="3"><x-spinner /></td>
			</tr>
		</tbody>
	</table>
</div>
