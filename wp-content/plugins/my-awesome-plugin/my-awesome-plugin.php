<?php
/*
    Plugin Name: My Awesome Plugin
    Plugin URI: http://solislab.com
    Description: Do some awesome stuff upon activation
    Author: Solis Lab
    Version: 0.1
    Author URI: http://solislab.com
    Text Domain: plugin-activation

From http://solislab.com/blog/plugin-activation-checklist/
*/
 
/**
 * This plugin does some awesome stuff upon activation:
 *
 * - Flush rewrite rules (if this has any custom post type or taxonomy)
 * - Display the "About" page whenever there's a new version
 * - Display tooltips
 * - Automatically deactivate itself if some conditions are not met
 */

class My_Awesome_Plugin {

    const VER = '0.1';
    const DB_VER = 3;
    
    public function bootstrap() {
    /* Setup the environment for the plugin */
        
        register_activation_hook( __FILE__, array( $this, 'activate' ) );
        
        add_action( 'admin_menu', array( $this, 'register_admin_menu' ) );
        add_action( 'plugins_loaded', array( $this, 'maybe_self_deactivate' ) );
        add_action( 'admin_init', array( $this, 'redirect_about_page' ), 1 );
        
        /* Hook into init and register post types and taxonomies */
        /* Flushing rewrite rules upon plugin activation is very important if the
           plugin needs to register custom post types (CPT) and/or custom taxonomies. */
        add_action( 'init', array( $this, 'register_post_types' ) );

        /* check for db version change on plugins_loaded */
        // Add 'maybe_update' to current $this array
        add_action( 'plugins_loaded', array( $this, 'maybe_update' ), 1 );
    }

    public function activate() {
    /* Do some stuff upon activation */
        
        $this->check_dependencies();
        $this->init_options();
        $this->maybe_update();
        
        // Flush rewrite rules so that users can access custom post types on the
        // front-end right away
        $this->register_post_types();
        flush_rewrite_rules();
        
        $this->activate_about_page();
    }
    
    public function check_dependencies() {
        /* Make sure bbpress has been activated or else self-destruct */
        // do nothing if class bbPress exists
        // if ( ! class_exists( 'bbPress' ) ) {
            // trigger_error( 'This plugin requires bbPress. Please install and activate bbPress before activating this plugin.', E_USER_ERROR );
        // }
    }

    public function register_admin_menu() {
    /* Register the "About My Awesome Plugin" page */
        // add_plugins_page( $page_title, $menu_title, $capability, $menu_slug, $function);
        add_plugins_page( 'About My Awesome Plugin', 'About M.A.P', 'manage_options', 'about-my-awesome-plugin', array( $this, 'display_about_page' ) );
    }

    public function maybe_self_deactivate() {
        /* If dependency requirements are not satisfied, self-deactivate */
        // if ( ! class_exists( 'bbPress' ) ) {
            // require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
            // deactivate_plugins( plugin_basename( __FILE__ ) );
            // add_action( 'admin_notices', array( $this, 'self_deactivate_notice' ) );
        // }
    }

    public function admin_notices() {
        /* Display an error message when the plugin deactivates itself. */
        // echo '<div class="error"><p>My Awesome Plugin has deactivated itself because bbPress is no longer active.</p></div>';
    }

    public function display_about_page() {
    /* Output the HTML for the about page */
        // output the HTML code here
        echo '<div class="wrap"><h2>Welcome to My Awesome Plugin</h2></div>';
    }

    public function activate_about_page() {
    /* Activate the auto redirection of the about page on the next page load */
        set_transient( 'solis_about_page_activated', 1, 30 );
    }

    public function redirect_about_page() {
    /* Redirect automatically to the About Page */

        // only do this if the user can activate plugins
        if ( ! current_user_can( 'manage_options' ) )
            return;

        // don't do anything if the transient isn't set
        if ( ! get_transient( 'solis_about_page_activated' ) )
            return;

        delete_transient( 'solis_about_page_activated' );
        wp_safe_redirect( admin_url( 'plugins.php?page=about-my-awesome-plugin') );
        exit;
    }
 
    public function maybe_update() {

        // bail if this plugin data doesn't need updating
        if ( get_option( 'solis_db_ver' ) >= self::DB_VER ) {
            return;
        }

        require_once( __DIR__ . '/update.php' );
        solis_update();
    }
 
    /* Initialize default option values */
    public function init_options() {
        update_option( 'solis_ver', self::VER );
        add_option( 'solis_db_ver', self::DB_VER );
        add_option( 'solis_posts_per_page', 10 );
        add_option( 'solis_show_welcome_page', true );
        // add_option( 'solis_coffee_consumed', 0 );
        add_option( 'solis_beverage_consumed', 0 );
    }

    /* Register post types and taxonomies */
    public function register_post_types() {
    }
}

global $my_awesome_plugin;
$my_awesome_plugin = new My_Awesome_Plugin();
$my_awesome_plugin->bootstrap();
