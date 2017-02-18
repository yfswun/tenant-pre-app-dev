<?php
/**
 * Plugin Name: Pre App Permissions
 * Description: Set permissions for Managing Buildings (Pods custom taxonomy) and customize the admin navigation menu for the Property Manager
 * Version:		1.0.0
 * Author:		Sylvia Wun
*/

// Add the custom capabilities to the building taxonomy
function set_building_capabilities($taxonomies) {
	$taxonomies[ 'building_taxonomy' ][ 'capabilities' ] = array(
		'assign_terms' => 'assign_building', 
		'edit_terms' => 'edit_building', 
		'manage_terms' => 'manage_building', 
		'delete_terms' => 'delete_building');
		return $taxonomies;
}
add_filter('pods_wp_taxonomies', 'set_building_capabilities');



// In the Pods Admin UI, go to Roles & Capabilities and add the new custom capabilities for the building taxonomy
// Add "manage_categories" capability for the Property Manager role. This is the capability used by Pods.



// For Property Manager, remove menus for editing categories and tags from the admin navigation menu
function remove_menu_pages_prop_mgr() {
	if ( current_user_can( 'property_manager' ) ) {
		remove_menu_page( 'edit-tags.php?taxonomy=category' );
		remove_menu_page( 'edit-tags.php?taxonomy=post_tag' );
	}
}
add_action( 'admin_init', 'remove_menu_pages_prop_mgr' );


// For Property Manager, remove menus from the toolbar
function remove_toolbar_menus_prop_mgr( $wp_admin_bar ) {
	if ( current_user_can( 'property_manager' ) ) {
		// WordPress
		$wp_admin_bar->remove_node( 'wp-logo' );
		// Ninja Forms
		$wp_admin_bar->remove_node( 'nf' );
	}
}
add_action( 'admin_bar_menu', 'remove_toolbar_menus_prop_mgr', 999 );


// (This does not work) Add the Buildings menu item to the admin navigation menu under Units
/*
function add_buildings_menu_item() {
	global $menu;
	global $submenu;
	$main_menu = 'Units';
	$sub_menu = 'Buildings';
	// echo '<div style="float:right">';
	foreach ( $menu as $mainMenu ) {
		if ( strtolower($mainMenu[0]) == strtolower($main_menu) ) {
			echo '<h1 style="float:right">Main menu exists</h1>';
			$UnitsPath = $mainMenu[2];
			echo '<pre style="float:right">';
			var_dump($UnitsPath);
			echo '<br />';
			echo '</pre>';
			$UnitsSubMenuItems = array_values( $submenu[ $UnitsPath ] );
			foreach ($UnitsSubMenuItems as $key => $value) {
				echo "<p style='float:right'><b>$key</b> => $value</p>";
				if ( strtolower($value[0]) == strtolower($sub_menu) ) {
					echo '<h1 style="float:right">Sub menu exists</h1>';
					return;
				}
			}
		}
	}
	echo '<h1 style="float:right">NEGATIVE</h1>';
	echo '</div>';
	$unitsMenuExists = 0;
	$bldgsSubMenuExists = 0;
	foreach ( $menu as $mainMenu ) {
		if ( strtolower($mainMenu[0]) == strtolower($main_menu) ) {
			$unitsMenuExists = 1;
			$UnitsPath = $mainMenu[2];
			return;
		}
	}
	
	if ( $unitsMenuExists == 1 ) {
		$UnitsSubMenuItems = array_values( $submenu[ $UnitsPath ] );
		foreach ($UnitsSubMenuItems as $key => $value) {
			if ( strtolower($value[0]) == strtolower($sub_menu) ) {
				$bldgsSubMenuExists = 1;
				return;
			}
		}
		if ( $bldgsSubMenuExists == 0 ) {
			// echo '<h1 style="float:right">Buildings sub menu does not exist</h1>';
		// } else {
			// echo '<h1 style="float:right">Buildings sub menu exists!</h1>';
			add_submenu_page(
				'edit.php?post_type=unit_type', 
				'Buildings', 
				'Buildings', 
				'manage_building', 
				'edit_buildings', 
				'edit-tags.php?taxonomy=building_taxonomy&post_type=unit_type'
			);
		}
	}
}
add_action( 'admin_init', 'add_buildings_menu_item' );
*/
?>