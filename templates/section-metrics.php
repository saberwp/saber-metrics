<!-- Metric save form and table. -->
<section id="section-metrics" class="sm-section">

	<header>METRICS</header>

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
			<tr>
				<th class="sort desc" data-sort="metric-id">ID</th>
				<th class="sort" data-sort="metric-title">Title</th>
			</tr>
		</thead>
		<tbody class="list">
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
		<tfoot>
			<tr>
				<td class="pagination" colspan="3"></td>
			</tr>
		</tfoot>
	</table>

	<div class="pagination"></div>

</section>
