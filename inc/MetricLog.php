<?php

namespace SaberMetrics;

class MetricLog {


	public function init() {

		// Metric AJAX hooks.
		add_action('wp_ajax_metric_log_save', array($this, 'metric_log_save_ajax'));
		add_action('wp_ajax_metric_log_delete', array( $this, 'metric_log_delete_ajax'));

	}

	public function metric_log_save_ajax() {

		$data = $_POST['data']; // Get the data from the AJAX request

		// Sanitize the data
    $data = array_map('sanitize_text_field', $data);

		// Run insert or update if ID is not 0.
		if( (int) $data['id'] === 0 ) {
			$id  = $this->create($data);
			$msg = 'Created successfully.';
		} else {
			$id  = $this->update($data);
			$msg = 'Updated successfully.';
		}


		// Send a JSON response using wp_send_json_success()
    $response_data = array(
			'code'    => 200,
      'message' => $msg,
      'data'    => $data,
			'row'			=> $this->fetch_one( $id ),
			'id'      => $id
    );
    wp_send_json_success($response_data);

	}

	public function metric_log_delete_ajax() {
    global $wpdb;
    $id = isset( $_POST['id'] ) ? intval( $_POST['id'] ) : 0;
      if ( $id ) {
        $table_name = $wpdb->prefix . 'metric_log';
        $result = $wpdb->delete( $table_name, array( 'id' => $id ) );

        if ( $result ) {
          $response = array(
            'success' => true,
            'id' => $id,
            'message' => 'Metric deleted successfully!'
          );
        } else {
          $response = array(
            'success' => false,
            'id' => $id,
            'message' => 'Error deleting metric!'
          );
        }
        wp_send_json( $response );
      }
      wp_die();
    }

		private function create( $data ) {

			global $wpdb;

			// Insert the data into the database
	    $wpdb->insert(
	      $wpdb->prefix . 'metric_log',
	      array(
					'metric_id' => $data['metric_id'],
					'value' => $data['value'],
	      ),
	      array('%d')
	    );

	    // Get the ID of the new metric
	    $id = $wpdb->insert_id;
			return $id;

		}

	private function update( $data ) {

		global $wpdb;

		// Insert the data into the database
    $wpdb->update(
      $wpdb->prefix . 'metric_log',
      array(
				'metric_id' => $data['metric_id'],
				'value' => $data['value'],
      ),
			array(
				'id' => $data['id'],
			),
      array('%d'),
			array('%d')
    );

    // Return the ID of the updated metric
		return $data['id'];
	}

	public static function fetch() {
		global $wpdb;
		$results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}metric_log ORDER BY id DESC LIMIT 10");
		return $results;
	}

	/*
	 * Fetch Filtered
	 * @return Array of metric_log database records.
	 */
	 public static function fetch_filtered( $metric_id, $start, $end ) {

     global $wpdb;
     $table_name = $wpdb->prefix . 'metric_log';

     $query = $wpdb->prepare(
         "SELECT * FROM $table_name WHERE metric_id = %d AND created BETWEEN %s AND %s ORDER BY id DESC",
         $metric_id,
         $start,
         $end
     );

     $results = $wpdb->get_results( $query );
     return $results;
	 }


	public function fetch_one( $id ) {
	  global $wpdb;
	  $table_name = $wpdb->prefix . 'metric_log';
	  $row = $wpdb->get_row(
      $wpdb->prepare(
        "SELECT * FROM $table_name WHERE id = %d",
        $id
      ),
	  );
	  return $row;
  }

	public function fetch_grouped($metric_id, $period, $start, $end) {
    global $wpdb;

    $interval = '';
    switch ($period) {
      case 'daily':
        $interval = 'DAY';
        break;
      case 'weekly':
        $interval = 'WEEK';
        break;
      case 'monthly':
        $interval = 'MONTH';
        break;
    }

    $query = $wpdb->prepare("
      SELECT
          COUNT(*) AS count,
          SUM(value) AS total,
          AVG(value) AS average,
          DATE_FORMAT(created, '%%Y-%%m-%%d') AS date
      FROM {$wpdb->prefix}metric_log
      WHERE created >= %s AND created <= %s AND metric_id = %d
      GROUP BY DATE_FORMAT(created, '%%Y-%%m-%%d')
    ", $start, $end, $metric_id);

    if ($interval) {
      $query = $wpdb->prepare("
          SELECT
            COUNT(*) AS count,
            SUM(value) AS total,
            AVG(value) AS average,
            DATE_FORMAT(created, '%%Y-%%m-%%d') AS date
          FROM {$wpdb->prefix}metric_log
          WHERE created >= %s AND created <= %s AND metric_id = %d
          GROUP BY YEAR(created), {$interval}(created)
      ", $start, $end, $metric_id);
    }

    $results = $wpdb->get_results($query, ARRAY_A);

    return $results;
}






}
