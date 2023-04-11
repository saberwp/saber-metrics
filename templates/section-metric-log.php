<!-- Metric log save form and table. -->
<section id="metric-log-section">
	<form id="metric-log-save-form">
		<input id="field-id" type="hidden" value="0">
		<div class="sm-field-group">
			<label for="field-metric-id">Metric</label>
			<select id="field-metric-id">
				<option value="13">13</option>
			</select>
		</div>
		<div class="sm-field-group">
			<label for="field-value">Value</label>
			<input id="field-value" type="text">
		</div>
		<div class="sm-field-group">
			<input type="submit" value="SAVE" />
		</div>
	</form>

	<!-- Render table. -->
	<table id="metric-log-table">
		<thead>
			<th>ID</th>
			<th>Value</th>
			<th>Metric</th>
			<th>Created</th>
			<th></th>
		</thead>
		<tbody>
			<?php
				$results = \SaberMetrics\MetricLog::fetch();
				if( ! empty( $results )) {
					foreach ($results as $result) {
			?>
				<tr id="metric-log-row-<?php echo $result->id; ?>">
					<td class="metric-log-id"><?php echo $result->id; ?></td>
					<td class="metric-log-value"><?php echo $result->value; ?></td>
					<td class="metric-log-metric-id"><?php echo $result->metric_id; ?></td>
					<td class="metric-log-created"><?php echo $result->created; ?></td>
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
