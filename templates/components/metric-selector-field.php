<select id="field-metric-id">
	<?php
		$metrics = \SaberMetrics\Metric::fetch();
		if( ! empty( $results )) {
			foreach( $metrics as $metric ) {
				echo '<option value="' . $metric->id . '">' . $metric->title . '</option>';
			}
		}
	?>
</select>
