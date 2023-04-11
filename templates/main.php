<header>
	<h1>SABER METRICS</h1>
</header>
<main>
	<section class="sm-app-section">
		<nav>
			<ul>
				<li>DASHBOARD</li>
				<li>METRICS</li>
				<li>REPORTS</li>
				<li>SETTINGS</li>
			</ul>
		</nav>
	</section>
	<section>
		<form id="model-save">
			<input id="field-id" type="hidden" value="0">
			<div class="sm-field-group">
				<label for="field-title">Title</label>
				<input id="field-title" type="text">
			</div>
			<div class="sm-field-group">
				<label for="field-prefix">Prefix</label>
				<input id="field-prefix" type="text">
			</div>
			<div class="sm-field-group">
				<label for="field-append">Append</label>
				<input id="field-append" type="text">
			</div>
			<div class="sm-field-group">
				<label for="field-rounding">Rounding</label>
				<input id="field-rounding" type="number" value="0">
			</div>
			<div class="sm-field-group">
				<input type="submit" value="SAVE" />
			</div>
		</form>

		<!-- Render table. -->
		<table id="metric-table">
			<thead>
				<th>ID</th>
				<th>Title</th>
				<th></th>
			</thead>
			<tbody>
				<?php
					$results = \SaberMetrics\Plugin::fetch_metrics();
					foreach ($results as $result) {

				?>

				<tr id="metric-row-<?php echo $result->id; ?>">
					<td class="metric-id"><?php echo $result->id; ?></td>
					<td class="metric-title"><?php echo $result->title; ?></td>
					<td>
						<button class="row-edit" data-id="<?php echo $result->id; ?>">EDIT</button>
						<button class="row-delete" data-id="<?php echo $result->id; ?>">DELETE</button>
					</td>
				</tr>

				<?php

					}
				?>

			</tbody>
		</table>

	</section>
</main>

<style>

main {
	display: flex;
	gap: 1rem;
}

.sm-field-group {
	margin: 0 0 1rem 0;
}

.sm-field-group label {
	display: block;
	margin: 0 0 3px 0;
}



nav {
	background-color: #F2F2F2;
	padding: 1rem;
	min-width: 12rem;
}

nav li:first-child {
	border-top: solid 1px #CCC;
}

nav li {
	cursor: pointer;
	padding: 0.5rem 0.5rem 0.5rem 0.5rem;
	margin: 0 0 0 0;
	border-bottom: solid 1px #CCC;
}

nav li:hover {
	background-color: #BBB;
	color: #FFF;
}

input[type=submit] {
	cursor: pointer;
}

</style>
