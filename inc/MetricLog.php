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
			$id = $this->create($data);
		} else {
			$id = $this->update($data);
		}


		// Send a JSON response using wp_send_json_success()
    $response_data = array(
			'code'    => 200,
      'message' => 'Data saved successfully!',
      'data'    => $data,
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
				'value' => $data['value'],
      ),
			array(
				'id' => $data['id'],
			),
      array('%d'),
			array('%d')
    );

    // Return the ID of the new metric
		return $data['id'];
	}

	public static function fetch() {
		global $wpdb;
		$results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}metric_log ORDER BY id DESC LIMIT 10");
		return $results;
	}

}
