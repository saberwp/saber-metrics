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
		$dates = $this->time_period_parse( $data['time_period'] );
		$metric_logs = MetricLog::fetch_filtered( $data['metric_id'], $dates->start, $dates->end );

		// Grouping.
		$dates = $this->time_period_parse( $data['time_period'] );
		$ml = new \SaberMetrics\MetricLog;
		$grouped = $ml->fetch_grouped($data['metric_id'], $data['grouping'], $dates->start, $dates->end);

		// Send a JSON response using wp_send_json_success()
    $response_data = array(
			'code'    => 200,
			'data'    => $data,
			'logs'    => $metric_logs,
			'grouped' => $grouped,
    );
    wp_send_json_success($response_data);

	}


	/**
	 * Returns reporting periods based on $value
	 *
	 * @param string $value The time period value
	 *
	 * @return object $dates The start and end dates
	 */
	 public function time_period_parse( $value ) {

		$dates = new \stdClass;

		switch ($value) {
			case 'today':
				$dates->start = date('Y-m-d');
				$dates->end = date('Y-m-d', strtotime('+1 day'));
				break;
			case 'week':
				$dates->start = date('Y-m-d', strtotime('last monday'));
				$dates->end = date('Y-m-d', strtotime('+1 day'));
				break;
			case '7_days':
				$dates->start = date('Y-m-d', strtotime('-6 days'));
				$dates->end = date('Y-m-d', strtotime('+1 day'));
				break;
			case '2_weeks':
				$dates->start = date('Y-m-d', strtotime('last monday -1 week'));
				$dates->end = date('Y-m-d', strtotime('+1 day'));
				break;
			case 'current_month':
				$dates->start = date('Y-m-01');
				$dates->end = date('Y-m-t', strtotime('now'));
				break;
			case '30_days':
				$dates->start = date('Y-m-d', strtotime('-29 days'));
				$dates->end = date('Y-m-d', strtotime('+1 day'));
				break;
			case '2_months':
				$dates->start = date('Y-m-01', strtotime('-1 month'));
				$dates->end = date('Y-m-t', strtotime('now'));
				break;
			case '3_months':
				$dates->start = date('Y-m-01', strtotime('-2 month'));
				$dates->end = date('Y-m-t', strtotime('now'));
				break;
			case 'current_year':
				$dates->start = date('Y-01-01');
				$dates->end = date('Y-12-31', strtotime('now'));
				break;
			case 'forever':
				$dates->start = date('Y-m-d', strtotime('-10 years'));
				$dates->end = date('Y-m-d', strtotime('+1 day'));
				break;
		}

		return $dates;
		}


}
