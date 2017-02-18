<?php
/**
 * Template Name: Full Width Building
 * Description: Full Width Template for Building Page
 */

__( 'Full Width Template', 'sg-window' );
get_header();
?>
<div class="main-wrapper">
	<div class="site-content"> 
	<?php
		if ( have_posts() ) : ?>
			<div class="content"> 
			<?php
				while ( have_posts() ) : the_post();
					// get_template_part( 'content', 'page' );
					get_template_part( 'templates/content', 'building' );
				endwhile;
			?>
			</div>	<!-- .content -->
			<div class="clear"></div>
	<?php
			// Begin: 09/23/15 Sylvia Wun
			// Page navigation for Properties

			// Commented
			// sgwindow_paging_nav();
			
			// 02/18/16 Sylvia Wun
			// Changed sort_column to menu_order, which is set by the Order attribute of the page 

			$parent_post = get_post( $post->post_parent );

			if ( get_the_title( $parent_post ) == 'Properties' ) {
				
				$args = array(
							'sort_order' => 'asc',
							'sort_column' => 'menu_order',
							'hierarchical' => 1,
							'parent' => $parent_post->ID
						); 
				$page_list = get_pages( $args );

				$pages = array();
				foreach ( $page_list as $page ) {
				   $pages[] += $page->ID;
				}

				$current = array_search( get_the_ID(), $pages );
				
				// $prevID = $pages[$current-1];
				$prev = $current - 1;
				if ( $prev >= 0 ) {
					$prevID = $pages[$prev];
				}
				// $nextID = $pages[$current+1];
				$next = $current + 1;
				if ( $next <= count( $pages ) - 1 ) {
					$nextID = $pages[$next];
				}
			}
	?>
			<nav class="navigation post-navigation" role="navigation">
				<h1 class="screen-reader-text">Page navigation</h1>
				<div class="nav-link">
				<!-- Next is BEFORE Previous -->
				<?php
					if ( !empty( $nextID ) ) {
				?>
						<a href="<?php echo get_permalink( $nextID ); ?>" rel="next">
							<span class="nav-next">
								<?php echo get_the_title( $nextID );?>&nbsp;&rarr;
							</span>
						</a>
				<?php
					}
				?>
				<?php
					if ( !empty( $prevID ) ) {
				?>
						<a href="<?php echo get_permalink( $prevID ); ?>" rel="prev">
							<span class="nav-previous-one">
								&larr;&nbsp;<?php echo get_the_title( $prevID ); ?>
							</span>
						</a>
				<?php
					}
				?>
				</div><!-- .nav-link -->
			</nav>
	<?php
		// End	: 09/23/15 Sylvia Wun
			
		else :
	?>
			<div class="content"> 
			<?php 
				get_template_part( 'content', 'none' );
			?>
			</div>	<!-- .content -->
	<?php 
		endif;
	?>
	</div>	<!-- .site-content -->
</div>	<!-- .main-wrapper -->
<?php
get_footer();
?>