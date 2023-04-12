<section id="section-reports" class="sm-section">

	<header>REPORTS</header>


	<!-- Report filters. -->
	<?php require_once(SABER_METRICS_PATH . 'templates/components/report-filters.php'); ?>

	<?php

		// Make report using fetch filtered.
		$results = \SaberMetrics\MetricLog::fetch_filtered(13, '2023-04-12');

		// Calculate total.
		$total = 0;
		if( ! empty( $results )) {
			foreach ($results as $result) {
				$total += $result->value;
			}
		}

		// Calculate count.
		$count = count($results);

		// Calculate average.
		$average = 0;
		if( $total > 0 && $count > 0 ) {
			$average =  $total / $count;
		}

	?>

	<!-- Container for stats. -->
	<section class="sm-stats-row"></section>

	<!-- Chart Render -->
	<?php

	// Make chart labels and data from results.
	$labels = array();
  $data = array();
	$results = array_reverse($results); // Reverse array so last entry is on the right of the chart.
	foreach ($results as $result) {
		array_push($labels, $result->created);
		array_push($data, $result->value);
	}

	// Convert to JSON.
	$labels_json = json_encode($labels);
  $data_json = json_encode($data);

	// Output JS for Chart.js.
	echo "<script>
          var saberMetricsChartData = $data_json;
          var labels = $labels_json;
        </script>";

	?>
	<canvas id="saber-chart"></canvas>

</section>
