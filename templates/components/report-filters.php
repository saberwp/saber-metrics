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
			<option value="today">Today</option>
			<option value="week">Current Week</option>
			<option value="7_days">7-days</option>
			<option value="2_weeks">2-weeks</option>
			<option value="current_month">Current Month</option>
			<option value="30_days">Past 30-days</option>
			<option value="2_months">2-months</option>
			<option value="3_months">3-months</option>
			<option value="current_year">Current Year</option>
			<option value="forever">Forever</option>
		</select>
	</div>
	<div class="sm-report-filter">
		<label>Grouping</label>
		<select id="sm-report-filter-grouping">
			<option value="log">Log</option>
			<option value="daily">Daily</option>
			<option value="weekly">Weekly</option>
			<option value="monthly">Monthly</option>
			<option value="quarterly">Quarterly</option>
			<option value="yearly">Yearly</option>
		</select>
	</div>
</section>
