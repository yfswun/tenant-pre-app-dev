<?php
/*
Plugin Name: Redirect PreApp Form
Description: Redirect the pre-application form to the submission page
Author:      Sylvia Wun
Version:     1.0
*/

function ninja_forms_handler() {
  add_action ( 'ninja_forms_post_process', 'set_pre_app_redirect_url', 1, 2 );
}
add_action('init', 'ninja_forms_handler');

function set_pre_app_redirect_url() {
    $pre_app_form_id = 5;
    global $ninja_forms_processing;
    $current_form_id = $ninja_forms_processing->get_form_ID();
    $redirect_url = site_url() . '/pre-application-submissions/';
echo $current_form_id;
    if ($current_form_id == $pre_app_form_id) {
        $ninja_forms_processing->update_form_setting( 'landing_page' , $redirect_url );
    }
}
// add_action ( 'ninja_forms_processing', 'set_pre_app_redirect_url', 1, 2 );

// function pre_app_success_redirect () {
    // $pre_app_form_id = 5;
    // //$user_id = Ninja_Forms()->sub( $sub_id )->user_id;;
    // //$user_id = $ninja_forms_processing->get_user_ID();
    // $args = array('form_id' => $pre_app_form_id);
    // $subs = Ninja_Forms()->subs()->get($args);
    // global $post;
    // if ( is_page() || is_object( $post ) ) {
        // if ( $redirect = get_post_meta($post->ID, 'redirect', true ) ) {
                        // wp_redirect( $redirect );
                        // exit;
                // }
        // }
// }
// add_action( 'get_header', 'pre_app_success_redirect' );
?>