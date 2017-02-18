<?php
/*
Plugin Name: Popular Post Views
Description: A plugin that records post views and contains functions to easily list posts by popularity
Version:     1.0
Reference:   http://www.smashingmagazine.com/2011/09/how-to-create-a-wordpress-plugin/
Author:      Sylvia Wun
*/


/**
 * Adds a view to the post being viewed
 *
 * Finds the current views of a post and adds one to it by updating
 * the postmeta. The meta key used is "pop_post_views".
 *
 * @global object $post The post object
 * @return integer $new_views The number of views the post has
 *
 */
function pop_post_add_view() {
   if(is_single()) {
      global $post;
      $current_views = pop_post_get_view_count();
      // $current_views = get_post_meta($post->ID, "pop_post_views", true);
      // if(!isset($current_views) OR empty($current_views) OR !is_numeric($current_views) ) {
         // $current_views = 0;
      // }
      $new_views = $current_views + 1;
      update_post_meta($post->ID, "pop_post_views", $new_views);
      return $new_views;
   }
}
add_action("wp_head", "pop_post_add_view");

/**
 * Retrieve the number of views for a post
 *
 * Finds the current views for a post, returning 0 if there are none
 *
 * @global object $post The post object
 * @return integer $current_views The number of views the post has
 *
 */
function pop_post_get_view_count() {
   global $post;
   $current_views = get_post_meta($post->ID, "pop_post_views", true);
   if(!isset($current_views) OR empty($current_views) OR !is_numeric($current_views) ) {
      $current_views = 0;
   }
   return $current_views;
}

/**
 * Shows the number of views for a post
 *
 * Finds the current views of a post and displays it together with some optional text
 *
 * @global object $post The post object
 * @uses awepop_get_view_count()
 *
 * @param string $singular The singular term for the text
 * @param string $plural The plural term for the text
 * @param string $before Text to place before the counter
 *
 * @return string $views_text The views display
 *
 */
function pop_post_show_views($singular = "view", $plural = "views", $before = "This post has: ") {
   global $post;
   $current_views = pop_post_get_view_count();

   $views_text = $before . $current_views . " ";

   if ($current_views == 1) {
      $views_text .= $singular;
   }
   else {
      $views_text .= $plural;
   }

   echo $views_text;

}

/**
 * Displays a list of posts ordered by popularity
 *
 * Shows a simple list of post titles ordered by their view count
 *
 * @param integer $post_count The number of posts to show
 *
 */
function pop_post_popularity_list($post_count = 10) {
    $args = array(
        "posts_per_page" => 10,
        "post_type" => "post",
        "post_status" => "publish",
        "meta_key" => "pop_post_views",
        "orderby" => "meta_value_num",
        "order" => "DESC"
    );

    $pop_post_list = new WP_Query($args);

    if ($pop_post_list->have_posts()) { echo "<ul>"; }

    while ( $pop_post_list->have_posts() ) : $pop_post_list->the_post();
        echo '<li><a href="' . get_permalink($post->ID) . '">' . the_title('', '', false) . '</a></li>';
    endwhile;

    if ($pop_post_list->have_posts()) { echo "</ul>";}
}
?>