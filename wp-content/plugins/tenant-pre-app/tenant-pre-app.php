<?php
/*
Plugin Name: Tenant Pre App
Plugin URI:  http://tenantapp.asianinc.org
Description: Plugin for ASIAN, Inc. Tenant Pre-Application
Author:      Sylvia Wun
Version:     1.0
Author URI:  http://tenantapp.asianinc.org
*/

define( 'TPA_COMPANY_NAME', 'ASIAN, Inc.' );
define( 'TPA_PROJECT_NAME', 'Tenant Pre-Application' );
define( 'PROP_APP_GDOC_URL', 'https://docs.google.com/uc?export=download&id=0Bxp9hNGllyo3MlN3T3Bia1Z6dTQ' );
define( 'HQ_GMAPS_URL', 'https://www.google.com/maps/place/1167+Mission+St,+San+Francisco,+CA+94103' );

define( 'TPA_DIR', plugin_dir_path( __FILE__ ) );
include( TPA_DIR . 'includes/preapp_submissions_function.php' );

define( 'LIMITS_URL', 'http://www.asianinc.org/wp-content/uploads/2014/10/PM-2015-Maximum-Income-and-Rent-Limits-by-Properties.pdf-06-26-152.pdf' );
?>