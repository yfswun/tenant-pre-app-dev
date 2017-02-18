<?php
/**
 * Plugin Name: List Admin Nav Items
 * Description: List all items in the admin navigation menu
 * Version:		1.0.0
 * Author:		Sylvia Wun
*/

function list_admin_nav_menus() {
	global $menu;
	global $submenu;
	echo '<div style="margin-left:100px">';
	echo "<pre>";
	print_r($menu);
	print_r($submenu);
	echo "</pre>";
	echo '</div>';
}
add_action( 'admin_menu', 'list_admin_nav_menus');