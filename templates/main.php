<main>

	<?php require_once(SABER_METRICS_PATH . '/templates/menu.php'); ?>
	<?php require_once(SABER_METRICS_PATH . '/templates/section-dashboard.php'); ?>
	<?php require_once(SABER_METRICS_PATH . '/templates/section-metrics.php'); ?>
	<?php require_once(SABER_METRICS_PATH . '/templates/section-tracker.php'); ?>
	<?php require_once(SABER_METRICS_PATH . '/templates/section-reports.php'); ?>
	<?php require_once(SABER_METRICS_PATH . '/templates/section-settings.php'); ?>

	<!-- Components -->
	<?php require_once(SABER_METRICS_PATH . '/templates/components/js-stat.php'); ?>

</main>

<?php

$ml = new \SaberMetrics\MetricLog;
$grouped = $ml->fetch_grouped(14, 'daily', '2023-04-10', '2023-04-13');

echo '<pre>';
var_dump( $grouped );
echo '</pre>';
