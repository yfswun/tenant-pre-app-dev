<?php
/*
Template Name: Users List
*/

$theme = 'sg-window';
get_header(); ?>

<div id="primary" class="site-content">
    <div id="content" role="main">
        <article>
            <header class="entry-header">
                <h1 class="entry-title"><?php _e( 'User Directory', $theme ); ?></h1>
            </header>
            <div class="entry-content">
                <ul>
                    <?php 
                    $users = get_users('orderby=user_lastname');
                    foreach ($users as $user) {
                        pods_user_profile_display($user);
                    }
                    ?>
                </ul>
            </div>
        </article>
    </div><!-- #content -->
</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>