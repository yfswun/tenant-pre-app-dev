<?php
/**
 * The template for displaying archive by Author
 *
 * @package WordPress
 * @subpackage sgwindow
 * @since SG Window 1.0.0
 */

$theme = 'sg-window';

get_header(); 
$sgwindow_layout = sgwindow_get_theme_mod( 'layout_archive' );
$sgwindow_layout_content = sgwindow_get_theme_mod( 'layout_archive_content' );
?>
<div class="main-wrapper <?php echo esc_attr( sgwindow_content_class( $sgwindow_layout_content ) ); ?> <?php echo esc_attr( $sgwindow_layout ); ?> ">
    
    <div class="site-content">
        <?php
        if ( have_posts() ) : 
        ?>
            <header class="archive-header">
                <h1 class="archive-title">
                    <?php
                    // the_post();

                    // --- 2015-09-10 Sylvia Wun: Begin --- //

                    // http://pods.io/tutorials/using-pods-create-user-directory/
                    /** Get all of the data about the author from the WordPress and Pods generated user profile fields. **/

                    // First get the_post so WordPress knows who's author page this is
                    the_post();
                    ?>
                    
                    <article>
                        <header class="entry-header">
                            <h1 class="entry-title"><?php _e('User Profile', $theme); ?></h1>
                        </header>	
                    
                        <?php 
                            //get the author's meta data and store in array $user
                            $user = get_userdata( get_the_author_meta('ID') );
                            //show the user's profile.
                            pods_user_profile_display($user);

                            // Since we called the_post() above, we need to rewind the loop back to the beginning that way we can run the loop properly, in full.
                            rewind_posts();
                    // --- 2015-09-10 Sylvia Wun: End --- //

                    printf( __( 'All posts by %s', $theme ), get_the_author() );
                ?>
                </h1>
            <?php
            // --- 2015-09-10 Sylvia Wun: Begin --- //
            // Commented below
                // if ( get_the_author_meta( 'description' ) ) : ?>
                <!-- <div class="author-description"> -->
                    <?php //the_author_meta( 'description' ); ?>
                <!--</div> -->
            <?php //endif;
            // --- 2015-09-10 Sylvia Wun: End --- //
            ?>
            
            </header><!-- .archive-header -->

            <div class="content"> 
                <?php
                // rewind_posts();
                while ( have_posts() ) : the_post();
                    get_template_part( 'content', sgwindow_get_content_prefix() );
                endwhile; ?>

                <div class="content-search">
                    <?php do_action( 'sgwindow_after_content' ); ?>
                </div><!-- .content-search -->
            
            </div><!-- .content -->
            
            <div class="clear"></div>
            
            <?php
            sgwindow_paging_nav();
            
        else :  
        ?>
            <div class="content"> 
                <?php 
                get_template_part( 'content', 'none' );
                ?>
            </div><!-- .content -->
        <?php 
        endif;
?>
	</div><!-- .site-content -->
	<?php
	sgwindow_get_sidebar( sgwindow_get_theme_mod( 'layout_archive' ) );
	?>
</div> <!-- .main-wrapper -->

<?php
get_footer();