<?php

namespace SaberMetrics;

class Report {

	public function init() {

		// Report AJAX hooks.
		add_action('wp_ajax_metric_report_data_fetch', array($this, 'data_fetch_request'));

	}

	public function data_fetch_request() {

		$data = $_POST['data']; // Get the data from the AJAX request

		// Sanitize the data
    $data = array_map('sanitize_text_field', $data);

		// Fetch metric logs.
		$metric_logs = MetricLog::fetch_filtered( $data['metric_id'], date('Y-m-d') );

		// Grouped experiment.
		$ml = new \SaberMetrics\MetricLog;
		$grouped = $ml->fetch_grouped($data['metric_id'], $data['grouping'], '2023-01-01', '2023-04-20');

		// Send a JSON response using wp_send_json_success()
    $response_data = array(
			'code'    => 200,
			'data'    => $data,
			'logs'    => $metric_logs,
			'grouped' => $grouped,
    );
    wp_send_json_success($response_data);

	}


}
