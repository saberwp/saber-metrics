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

	<!-- Metric save form and table. -->
	<section id="metric-section">
		<form id="metric-save-form">
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
					$results = \SaberMetrics\Metric::fetch();
					if( ! empty( $results )) {
						foreach ($results as $result) {
				?>
					<tr id="metric-row-<?php echo $result->id; ?>">
						<td class="metric-id"><?php echo $result->id; ?></td>
						<td class="metric-title"><?php echo $result->title; ?></td>
						<td>
							<button class="sm-table-button row-edit" data-id="<?php echo $result->id; ?>">EDIT</button>
							<button class="sm-table-button row-delete" data-id="<?php echo $result->id; ?>">DELETE</button>
						</td>
					</tr>
				<?php
					} }
				?>
			</tbody>
		</table>

	</section>

	<?php require_once(SABER_METRICS_PATH . '/templates/section-metric-log.php'); ?>

</main>
