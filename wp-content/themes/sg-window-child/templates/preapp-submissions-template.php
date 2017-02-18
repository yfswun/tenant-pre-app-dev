<?php
/**
 * Template Name: Preapp Submissions
 * Description:   Template for the pre-application submissions page
 */

global $ninja_forms_processing;

// redirect to the form page if the user reloaded the form results page
if ( !isset( $ninja_forms_processing ) ) {
	$slug = 'pre-application-form';
	$url = home_url( '/' ) . $slug;
	// redirect after header definitions - cannot use wp_redirect($location);
?>
	<script>
	<!--
	window.location= <?php echo esc_url( "'" . $url . "'" ); ?>;
	//-->
	</script>
<?php
	exit;
}

__( 'Full Width Template', THEME );
get_header(); ?>

<div class="main-area">
		<div class="site-content">
			<div class="content">
				<div class="content-container">
					<article>
						<header class="entry-header">
							<div class="FormLinks clearfix">
								<a class="FormAction" href="javascript:window.print()">
									<span class="fa-stack fa-2x">
										<i class="fa fa-square fa-stack-2x" aria-hidden="true"></i>
										<i class="fa fa-print fa-stack-1x fa-inverse" aria-hidden="true"></i>
									</span>
									<span class="LinkText">Print This Page</span>
								</a>
								<a id="email-link"
									class="FormAction"
									href="<?php esc_url( admin_url( 'admin-ajax.php?action=send_email_sub_result&sub_id=' . get_the_ID() ) ); ?>"
									data-sub_id="<?php get_the_ID(); ?>"
									data-resultHTML=""
								>
									<span class="fa-stack fa-2x">
										<i class="fa fa-square fa-stack-2x" aria-hidden="true"></i>
										<i class="fa fa-envelope fa-stack-1x fa-inverse" aria-hidden="true"></i>
									</span>
									<span class="LinkText">Send Confirmation Email</span>
								</a>
							</div>
							<h1 class="entry-title"><?php _e( TPA_PROJECT_NAME . ' Submission Result', THEME ); ?></h1>
						</header>
						<div class="entry-content">
							<?php get_preapp_submissions(); ?>
						</div><!-- #entry-content -->
					</article>
				</div><!-- #content-container -->
			</div><!-- #content -->
		</div><!-- #site-content -->
</div><!-- #main-area -->

<?php get_footer(); ?>