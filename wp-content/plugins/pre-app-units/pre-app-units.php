<?php
/*
Plugin Name: Pre-App Units
Plugin URI:  http://www.asianinc.org
Description: Database interactions for pre-qualified units for each pre-application submission
Author:      Sylvia Wun
Version:     1.0
Author URI:  http://www.asianinc.org
*/

global $verPreAppUnitsTable;
$verPreAppUnitsTable = '1.0';

register_activation_hook( __FILE__, 'CreatePreAppUnitsTable' );

function CreatePreAppUnitsTable () {

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

// Add confirmation for pre-application form

function RegisterPreAppConfirmation() {
    add_action( 'ninja_forms_before_pre_process', 'PreAppConfirmation' );
}
add_action( 'init', 'RegisterPreAppConfirmation' );


function PreAppConfirmation(){
    
    global $ninja_forms_processing;
/* 
    $sub_id = $ninja_forms_processing->get_form_setting( 'sub_id' );
    $sub = Ninja_Forms()->sub($sub_id);
    
    // Applicant information
    $firstName = $sub->get_field(6);
    $lastName = $sub->get_field(7);
    $householdSize = (int) $sub->get_field(24);
    $annualGrossIncome = (float) $sub->get_field(25);
    $annualAssetAmt = (float) $sub->get_field(27);
    $totalAnnualIncome = (float) $sub->get_field(29);

    echo "<form action='confirm.php' method='post'>";
    echo "<input type='text' name='firstName' value='" . htmlspecialchars($firstName) . "' readonly />";
    echo "<input type='text' name='lastName' value='" . htmlspecialchars($lastName) . "' readonly />";
    echo "<input type='submit' name='submit' value='Submit' onclick='return ShowConfirm();' />";
    echo "</form>";
 */}
?>