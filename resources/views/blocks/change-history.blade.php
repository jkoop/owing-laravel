@props(['model'])

@once
	@vite('resources/css/change-history.css')
@endonce

<h2>Change history</h2>

<table class="change-history">
	<thead>
		<tr>
			<th>Date</th>
			<th>Author</th>
			<th>Description</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($model->changes()->with('author')->orderByDesc('created_at')->orderByDesc('id')->get() as $change)
			<tr>
				<td><x-datetime :datetime="$change->created_at" /></td>
				<td><x-user :user="$change->author" noName="system" /></td>
				<td>{{ $change->description }}</td>
			</tr>
		@endforeach
	</tbody>
</table>
