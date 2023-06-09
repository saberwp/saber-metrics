<?php

namespace SaberMetrics;

class Database {

	public function install() {

		$this->metric_table();
		$this->metric_log_table();

	}

	public function metric_table() {

		global $wpdb;
	  $charset_collate = $wpdb->get_charset_collate();

	  $table_name = $wpdb->prefix . 'metric';
		$sql = "CREATE TABLE $table_name (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      title varchar(255) NOT NULL,
      prefix varchar(10) NULL,
      append varchar(10) NULL,
      rounding varchar(10) NULL,
      created datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
      updated datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (id)
    ) $charset_collate;";

	  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	  dbDelta( $sql );

	}

	public function metric_log_table() {

		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();

		$table_name = $wpdb->prefix . 'metric_log';
		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			metric_id mediumint(9) NOT NULL,
			value mediumint(9) NOT NULL,
			time_period varchar(10) NULL,
			date_start datetime NULL,
			date_end datetime NULL,
			created datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
			updated datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

	}

}
