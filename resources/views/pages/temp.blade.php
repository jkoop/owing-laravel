<style>
	* {
		box-sizing: border-box;
		border-collapse: collapse;
		margin: 0;
		padding: 0;
	}

	.page {
		padding: 6mm;
		width: 120mm;
		height: 180mm;
	}

	/* .index {
		background-color: #08f2;
	} */
	.index .title {
		height: 20mm;
		font-size: 9mm;
		text-align: center;
	}

	.index td,
	.index th {
		height: 7mm;
		border: 0.25mm solid #888;
		border-left-color: #8888;
		border-right-color: #8888;
	}

	.index td:nth-child(1),
	.index th:nth-child(1) {
		width: calc(120mm - 12mm - 15mm);
	}

	.index td:nth-child(2),
	.index th:nth-child(2) {
		width: 15mm;
	}
</style>

<div class="page index">
	<div class="title">INDEX</div>
	<table>
		<tr>
			<th>SECTION</th>
			<th>PAGE</th>
		</tr>
		<tr data-repeat="19">
			<td></td>
			<td></td>
		</tr>
	</table>
</div>

<script>
	document.querySelectorAll('[data-repeat]').forEach(repeatable => {
		const repeat = parseInt(repeatable.dataset.repeat);
		let clone;

		repeatable.removeAttribute('data-repeat');

		for (let i = 0; i < repeat; i++) {
			clone = repeatable.cloneNode(true);
			repeatable.after(clone);
		}
	});
</script>
