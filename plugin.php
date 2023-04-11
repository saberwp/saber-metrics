<?php

/*
 * Plugin Name: Saber Metrics
 * Version: 1.0.3
 */



namespace SaberMetrics;

define('SABER_METRICS_PATH', \plugin_dir_path(__FILE__));
define('SABER_METRICS_URL', \plugin_dir_url(__FILE__));

class Plugin {

	public function __construct() {

		require_once(SABER_METRICS_PATH . '/inc/Database.php');

		// Plugin activation hook.
		register_activation_hook( __FILE__, array( $this, 'activate' ) );

		// Metric AJAX hooks.
		add_action('wp_ajax_metric_save', array($this, 'metric_save_ajax'));
		add_action('wp_ajax_metric_delete', array( $this, 'metric_delete_ajax'));

		// Register the menu page with the WP Admin menu
		add_action( 'admin_menu', function() {
			add_menu_page(
		    'Saber Metrics',      // Page title
		    'Saber Metrics',      // Menu title
		    'manage_options', // Capability required to access the menu page
		    'saber-metrics',      // Menu slug
		    array( $this, 'menu_page' ), // Function to display the menu page
		    'dashicons-admin-plugins' // Icon URL or CSS class for the menu icon
		  );
		});

	}

	// Define the function that will create the menu page
	public function menu_page() {
	  require_once(SABER_METRICS_PATH . '/templates/main.php');
	}

	public function activate() {

		$database = new \SaberMetrics\Database;
		$database->install();

  }

	public function metric_save_ajax() {

		global $wpdb;
		$data = $_POST['data']; // Get the data from the AJAX request

		// Sanitize the data
    $data = array_map('sanitize_text_field', $data);

		// Insert the data into the database
    $wpdb->insert(
      $wpdb->prefix . 'metric',
      array(
				'title' => $data['title'],
        'prefix' => $data['prefix'],
        'append' => $data['append'],
        'rounding' => $data['rounding']
      ),
      array('%s', '%s', '%s', '%d')
    );

    // Get the ID of the new metric
    $id = $wpdb->insert_id;

		// Send a JSON response using wp_send_json_success()
    $response_data = array(
			'code'    => 200,
      'message' => 'Data saved successfully!',
      'data'    => $data,
			'id'      => $id
    );
    wp_send_json_success($response_data);

	}

	public function metric_delete_ajax() {
    global $wpdb;
    $id = isset( $_POST['id'] ) ? intval( $_POST['id'] ) : 0;
      if ( $id ) {
        $table_name = $wpdb->prefix . 'metric';
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

	public static function fetch_metrics() {
		global $wpdb;
		$results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}metric ORDER BY id DESC LIMIT 10");
		return $results;
	}

}

new Plugin();
