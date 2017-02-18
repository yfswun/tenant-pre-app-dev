<?php 

/**
 * Plugin Name: Ajax Test
 * Description: This is a plugin that allows us to test Ajax functionality in WordPress
 * Version:     1.0.0
 * Author:      Sylvia Wun
 */

add_action( 'wp_enqueue_scripts', 'ajax_test_enqueue_scripts' );
function ajax_test_enqueue_scripts() {
    wp_enqueue_script( 'test', plugins_url( '/test.js', __FILE__ ), array('jquery'), '1.0', true );
}

?>