<?php

/*
 * Plugin Name: Saber Metrics
 * Author: SaberWP
 * Description: Metrics tracking plugin for WordPress.
 * Version: 1.0.6
 */



namespace SaberMetrics;

define('SABER_METRICS_PATH', \plugin_dir_path(__FILE__));
define('SABER_METRICS_URL', \plugin_dir_url(__FILE__));
define('SABER_METRICS_VERSION', '1.0.5');

class Plugin {

	public function __construct() {

		require_once(SABER_METRICS_PATH . '/inc/Database.php');
		require_once(SABER_METRICS_PATH . '/inc/Metric.php');
		require_once(SABER_METRICS_PATH . '/inc/MetricLog.php');
		require_once(SABER_METRICS_PATH . '/inc/Report.php');

		// Metric init.
		$metric = new Metric();
		$metric->init();

		// Metric log init.
		$metric_log = new MetricLog();
		$metric_log->init();

		// Reports init.
		$report = new Report();
		$report->init();

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

		// Admin scripts.
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

	}

	// Define the function that will create the menu page
	public function menu_page() {
	  require_once(SABER_METRICS_PATH . '/templates/main.php');
	}

	public function activate() {
		$database = new \SaberMetrics\Database;
		$database->install();
  }

	public function admin_scripts() {

		// CSS
		wp_enqueue_style( 'saber-metrics-main', SABER_METRICS_URL . '/css/main.css', array(), SABER_METRICS_VERSION, 'all' );

		// Javascript

		wp_enqueue_script( 'list-js', 'https://cdn.jsdelivr.net/npm/list.js@2.3.1/dist/list.min.js', array(), '2.3.1', true );

		wp_enqueue_script( 'saber-metrics-chart-js', 'https://cdn.jsdelivr.net/npm/chart.js@3.6.2/dist/chart.min.js', array( 'jquery' ), '3.6.2', true );
		wp_enqueue_script( 'saber-metrics-main', SABER_METRICS_URL . '/js/main.js', array( 'jquery', 'saber-metrics-chart-js' ), SABER_METRICS_VERSION, true );
		wp_enqueue_script( 'saber-metrics-metric-log', SABER_METRICS_URL . '/js/MetricLog.js', array( 'jquery', 'list-js' ), SABER_METRICS_VERSION, true );

		wp_enqueue_script( 'saber-metrics-chart', SABER_METRICS_URL . '/js/Chart.js', array( 'saber-metrics-main' ), SABER_METRICS_VERSION, true );
		wp_enqueue_script( 'saber-metrics-reports', SABER_METRICS_URL . '/js/Reports.js', array( 'jquery' ), SABER_METRICS_VERSION, true );

		wp_enqueue_script( 'saber-metrics-section-tracker', SABER_METRICS_URL . '/js/SectionTracker.js', array( 'jquery' ), SABER_METRICS_VERSION, true );
		wp_enqueue_script( 'saber-metrics-menu', SABER_METRICS_URL . '/js/Menu.js', array( 'jquery', 'saber-metrics-section-tracker', 'saber-metrics-reports' ), SABER_METRICS_VERSION, true );

	}

}

new Plugin();
