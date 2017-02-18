<?php
/**
 * Plugin Name: Pre App Disable Admin Bar
 * Description: Disable the Admin Bar (toolbar) for Non-Administrators or property managers. It will only be displayed in the admin panel using the admin url.
 * Version:     1.0.0
 * Author:      Sylvia Wun
*/

add_action('after_setup_theme', 'remove_admin_bar');

function remove_admin_bar() {
	show_admin_bar( false );
	if ( is_admin() && ( current_user_can( 'administrator' ) || current_user_can( 'property_manager' ) ) ) {
		show_admin_bar( true );
	}
	// show admin bar for developer swun
	if ( wp_get_current_user() -> user_email == 'swun@asianinc.org' ) {
		show_admin_bar( true );
	}
}
?>