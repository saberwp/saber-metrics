<section class="sm-report-filters">
	<div class="sm-report-filter">
		<label>Metric</label>
		<?php
			$field_id = 'sm-report-filter-metric';
			require(SABER_METRICS_PATH.'templates/components/metric-selector-field.php');
		?>
	</div>
	<div class="sm-report-filter">
		<label>Time Period</label>
		<select id="sm-report-filter-time-period">
			<option value="1">Today</option>
			<option value="2">Weekly</option>
			<option value="4">Monthly</option>
			<option value="5">Quarterly</option>
			<option value="6">Annually</option>
		</select>
	</div>
	<div class="sm-report-filter">
		<label>Grouping</label>
		<select id="sm-report-filter-grouping">
			<option value="1">Log</option>
			<option value="2">Daily</option>
			<option value="3">Weekly</option>
			<option value="4">Monthly</option>
			<option value="5">Quarterly</option>
			<option value="6">Annually</option>
		</select>
	</div>
</section>
