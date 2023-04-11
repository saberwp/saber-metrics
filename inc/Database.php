<?php

namespace SaberMetrics;

class Database {

	public function install() {



	}

	public function metrics_table() {

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


}
