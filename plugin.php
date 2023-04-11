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
		require_once(SABER_METRICS_PATH . '/inc/Metric.php');

		// Metric init.
		$metric = new Metric();
		$metric->init();

		// Plugin activation hook.
		register_activation_hook( __FILE__, array( $this, 'activate' ) );

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

	public static function fetch_metrics() {
		global $wpdb;
		$results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}metric ORDER BY id DESC LIMIT 10");
		return $results;
	}

}

new Plugin();
