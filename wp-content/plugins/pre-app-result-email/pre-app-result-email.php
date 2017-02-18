<?php

/**
 * Plugin Name: Pre App Result Email
 * Description: A plugin to email the tenant pre-application result page to the user
 * Version:     1.0.0
 * Author:      Sylvia Wun
 */

// In development environment everything is reloaded from source all the time but a productive server takes advantage of caches
// http://wpengineer.com/2292/force-reload-of-scripts-and-stylesheets-in-your-plugin-or-theme/
define ('VERSION', '1.0.0');
function version_id() {
	if ( WP_DEBUG ) {
		return time();
	}
	return VERSION;
}

add_action( 'wp_enqueue_scripts', 'pre_app_result_enqueue_scripts' );
function pre_app_result_enqueue_scripts() {
	global $post;
	if ( isset( $post ) ) {
		$slug = get_post( $post )->post_name;
		if ( $slug == 'pre-application-submissions' ) {
			wp_enqueue_script( 'pre_app_subs_email', plugins_url( '/pre_app_result_email.js', __FILE__ ), array('jquery'), version_id(), true );
			wp_localize_script(
				'pre_app_subs_email', 
				'TPA_email', 
				array(
					'ajax_url' => admin_url( 'admin-ajax.php' ),
					'TPA_email_nonce' => wp_create_nonce( 'nonce_string' ),
				)
			);
		}
	}
}

// this hook is fired if the current viewer is not logged in
// add_action( 'wp_ajax_nopriv_send_email_sub_result', '' );

// is logged in
add_action( 'wp_ajax_send_email_sub_result', 'send_email_sub_result' );


function send_email_sub_result() {

	// check to see if the submitted nonce matches with the generated nonce we created earlier
	check_ajax_referer( 'nonce_string', 'TPA-nonce' );
	
	$_POST = stripslashes_deep( $_POST );
	
	// Send an email with the pre-application submission result to the user

	$headers[] = 'From: ' . TPA_COMPANY_NAME . ' <' . get_bloginfo('admin_email') . '>';
	$headers[] = 'Content-Type: text/html; charset=UTF-8';

	$to_email = $_POST['email'];
	$to = $_POST["first_name"] . ' ' . $_POST["last_name"] . ' <' . $to_email . '>';
	
	$subject = $_POST["title_text"] . ' - ' . TPA_COMPANY_NAME;

	ob_start();
	include( 'email_header.php' );
	include( 'email_body.php' );
	include( 'email_footer.php' );
	$message = ob_get_contents();
	ob_end_clean();
	
	$message = replace_css( $message );

	// Prevent GMail from trimming the footer: Make the content unique
	// http://stackoverflow.com/questions/11078264/how-to-get-rid-of-show-trimmed-content-in-gmail-html-emails
	$message .= '<p style="padding-top:10px; font-size:50%; text-align:center;">* --- ' . date('r') . ' --- * </p>';
	
	$email_sent = wp_mail( $to, $subject, $message, $headers );
	if ( $email_sent ) {
		echo 'Success';
	} else {
		echo 'Failure';
	}
	
	wp_die();
}


// Replace the CSS classes in the email content with inline CSS

function replace_css( $str ) {

	$div_style = 'width:100%;';
	$line_spacing = 'line-height:120%;';
	$h2_font_size = 'font-size:14px;';
	$table_style = 'border-collapse:collapse; border:1px solid #999999; margin-top:3px; margin-bottom:5px;';
	$align_center = 'text-align:center;';
	$align_right = 'text-align:right;';
	$th_color = 'color:#b20000;';
	$td_color = 'color:#303030;';
	$padding3 = 'padding:3px;';
	$padding5 = 'padding:5px;';
	$clearfix = 'overflow:hidden;';
	
	$classes = array(
		'/<div class="TPASubForm"/',
		'/<div class="TPASubForm clearfix"/',
		'/<table\b[^>]*>/',
		'/<thead\b[^>]*>/',
		'/<tbody\b[^>]*>/',
		'/<tr\b[^>]*>/',
		'/<th\b[^>]*>/',
		'/<td(?=[^>]*?class="[^>]*?AlignCenter[^>]*?")[^>]*?>/',
		'/<td(?=[^>]*?class="[^>]*?AlignRight[^>]*")[^>]*?>/',
		'/<p class="TPASubForm">/',
		'/ class="TPASubForm"/',
		'/class="TPATableWrapper"/',
		'/<h2>/',
		'/id="SubmissionDT"/',
		'/<p>/',
	);

	$inline_css = array(
		'<div style="' . $div_style . '"',
		'<div style="' . $div_style . ' ' . $clearfix . '"',
		'<table style="' . $table_style . '">',
		'<thead>',
		'<tbody>',
		'<tr>',
		"<th style='$th_color $align_center $table_style $line_spacing $padding3'>",
		"<td style='$td_color $align_center $table_style $line_spacing $padding3'>",
		"<td style='$td_color $align_right $table_style $line_spacing $padding3'>",
		'<p style="' . $line_spacing . '">',
		'',
		'style="overflow:auto;"',
		'<h2 style="' . $h2_font_size . '">',
		'style="float:right; text-align:right; ' . $line_spacing . '"',
		'<p style="' . $line_spacing . '">',
	);
	
	$out = preg_replace($classes, $inline_css, $str);

	return $out;
}
?>