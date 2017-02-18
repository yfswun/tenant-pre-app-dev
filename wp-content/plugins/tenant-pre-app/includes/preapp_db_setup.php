<?php
/*
Created:     02/17/17
Updated:     02/17/17
Description: Create database tables for tenant pre-application
Author:      Sylvia Wun
*/

// pre_app_units

global $verPreAppUnitsTable;
$verPreAppUnitsTable = '1.0';

register_activation_hook( __FILE__, 'CreatePreAppSubFailsTable' );

function CreatePreAppSubFailsTable () {

    global $verPreAppUnitsTable;
    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();

    $tblName = $wpdb->prefix . "pre_app_units"; 

    $sql = "CREATE TABLE $tblName (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      sub_id mediumint(9) NOT NULL,
      pre_app_unit_slug varchar(100) NOT NULL,
      UNIQUE KEY id (id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
    
    add_option( 'pre_app_units_table_version', $verPreAppUnitsTable );
}


// pre_app_sub_fails
// Pre-application submission failures: under/over-qualified

global $verPreAppSubFailsTable;
$verPreAppSubFailsTable = '1.0';

register_activation_hook( __FILE__, 'CreatePreAppSubFailsTable' );

function CreatePreAppSubFailsTable () {

    global $verPreAppSubFailsTable;
    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();

    $tblName = $wpdb->prefix . "pre_app_sub_fails";

    $sql = "CREATE TABLE $tblName (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      sub_id mediumint(9) NOT NULL,
      under_qualified tinyint(1) default 0,
      over_qualified tinyint(1) default 0,
      UNIQUE KEY id (id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
    
    add_option( 'pre_app_sub_fails_table_version', $verPreAppSubFailsTable );
}
?>