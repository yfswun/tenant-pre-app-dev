<?php
/*
Template Name: Buildings List
*/
get_header();
$taxonomy = 'building_taxonomy';

$buildings = get_terms($taxonomy);
foreach($buildings as $building) {
    $units = new WP_Query(array(
        'post_type' => 'unit_type',
        'post_per_page'=>-1,
        'taxonomy'=>$taxonomy,
        'term' => $building->slug,
    ));
    $link = get_term_link(intval($building->term_id),$taxonomy);
    echo "<h2><a href=\"{$link}\">{$building->name}</a></h2>";
    echo '<h3>Units</h3>';
    echo '<ul>';
        while ( $units->have_posts() ) {
            $units->the_post();
            $link = get_permalink($post->ID);
            $title = get_the_title();
            echo "<li><a href=\"{$link}\">{$title}</a></li>";
        }
    echo '</ul>';
}
get_footer();
?>
