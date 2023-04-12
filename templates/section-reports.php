<section id="section-reports" class="sm-section">

	<header>REPORTS</header>

	<!-- Reporting -->
	<?php

	$results = \SaberMetrics\MetricLog::fetch();
	if( ! empty( $results )) {
		$total = 0;
		foreach ($results as $result) {
			$total += $result->value;
		}
	}

	$count = count( $results );

	$average = 0;
	if( $total > 0 && $count > 0 ) {
		$average =  $total / $count;
	}

	echo '<div>';
	echo '<h2>Count: ' . $count . '</h2>';
	echo '<h2>Total: ' . number_format( $total ) . '</h2>';
	echo '<h2>Average: ' . number_format( $average ) . '</h2>';
	echo '</div>';

	?>
</section>
