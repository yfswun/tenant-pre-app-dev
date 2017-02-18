<?php

/*
Template Name: Taxonomy Building Tax
*/

get_header();
$sgwindow_layout = sgwindow_get_theme_mod('layout_default');
echo '<div class="main-wrapper "' . esc_attr( $sgwindow_layout ) . '>';
echo '<div class="site-content">';
echo '<div class="content">';

$taxonomy = 'building_taxonomy';

$args=array(
'limit' => -1,
'orderby' => 'building_name',
'order' => 'ASC' ,
'hide_empty' => 0
);

// The extra fields in the taxonomy are pod fields so you have to make a pod object
$podterms = pods($taxonomy);

// Get terms for post using typical wordpress terms loop
// $terms = get_the_terms( $post->ID , 'product_tag' );

// Get terms for the WordPress taxonomy
// $terms = get_the_terms( $post->ID , 'building_taxonomy' );
$terms = get_terms($taxonomy, $args);

/* Only displays building names */
/*
//Put term ids in an array.
$ids = array();
foreach ( $terms as $term ) {
	$ids[] = $term->term_id;
}
//check that we found any terms, if so build a Pods obect, if not skip it.
if ( ! empty( $ids ) ) {
	//build where clause to query by term_id(s)
	//Looping AND instead of using IN() is a workaround for https://github.com/pods-framework/pods/issues/1978
	$i = 0;
	foreach( $ids as $id ) {
		if ( $i === 0 ) {
			$where = 't.term_id = "'.$id.'"';
		}
		else {
			$where .= ' OR t.term_id = "'.$id.'"';
		}
	
		$i++;
	
	}
	
	//build pods object
	$params = array (
		'where' => $where,
		'limit' => 5,
	);
	$taxonomy = pods( $taxonomy, $params );
	
	//loop if there are matching terms, if there are not something ver wrong has happened.
	if ( $taxonomy->total() > 0 ) {
		while ( $taxonomy->fetch() ) {
	
			//echo custom field of taxonomy
			echo $taxonomy->display( 'building_name' );
		}
	
		//paginate if needed
		echo $taxonomy->pagination();
	
	}
}
*/

echo "<h2 class='bldg-list'>Buildings</h2>";

//Loop through the WordPress taxonomy array
foreach($terms as $term) {

   //Fetch pod data
   $podterms->fetch($term->term_id);

   //The extra fields pod data
   $bldg_name = $podterms->field('building_name');
   $bldg_address = $podterms->field('building_address');
   $bldg_city = $podterms->field('building_city');
   $bldg_state = $podterms->field('building_state');
   $bldg_zip = $podterms->field('building_zip');
   $bldg_surveillance = $podterms->field('building_surveillance');
   $bldg_elevator = $podterms->field('building_elevator');
   $bldg_gated = $podterms->field('building_gated');
   $bldg_laundry = $podterms->field('building_laundry');
   $bldg_image_res_id = $podterms->field('building_image_resource_id');

// echo '<p>';
// var_dump($podterms);
// echo '</p>';

    //The WordPress taxonomy data
    // $tax_slug = $term->slug;
    // $tax_name = $term->name;
    // $tax_description = $term->description;
    // $tax_count = $term->count;
    //echo everything

// echo "<b>field: " . $podterms->field('building_image_resource_id') . "</b>";
// echo "<b>bldg_image_res_id: " . $bldg_image_res_id . "</b>";

    $img_url = home_url() . '/wp-content' . $bldg_image_res_id;
    echo "<ul class='bldg-l1'><img class='size-thumbnail' src='$img_url' alt='$bldg_name' height='100' width='100' />&nbsp;$bldg_name<br />";

    $units = new WP_Query(array(
        'post_type'     => 'unit_type',
        'post_per_page' => -1,
        'taxonomy'      => $taxonomy,
        'term'          => $term->slug,
        'order'         => 'ASC',
        'orderby'       => 'title'
    ));
    $link = get_term_link(intval($term->term_id),$taxonomy);
    // echo "<h2><a href=\"{$link}\">{$building->name}</a></h2>";
    echo '<h3>Units</h3>';
    echo '<ul>';
        while ( $units->have_posts() ) {
            $units->the_post();
            $link = get_permalink($post->ID);
            $title = get_the_title();
            echo "<li><a href=\"{$link}\">{$title}</a></li>";
        }
    echo '</ul>';

    echo "<li class='bldg-l2'>Address<br />";
            echo "$bldg_address<br />";
            echo "$bldg_city, $bldg_state $bldg_zip";
        echo '</li>';
        echo "<li class='bldg-l2'>Amenities<br />";
            echo '<ul>';
               $cnt = 0;
               if ($bldg_surveillance) {
                  echo "<li class='bldg-l3'>Onsite Surveillance Video Cameras</li>";
                  $cnt++;
               }
               if ($bldg_elevator) {
                  echo "<li class='bldg-l3'>Onsite Elevator</li>";
                  $cnt++;
               }
               if ($bldg_gated) {
                  echo "<li class='bldg-l3'>Gated Community</li>";
                  $cnt++;
               }
               if ($bldg_laundry) {
                  echo "<li class='bldg-l3'>Onsite Laundry</li>";
                  $cnt++;
               }
               if ($cnt == 0) {
                  echo "<li class='bldg-l3'>None</li>";
               }
            echo '</ul>';
        echo '</li>';
    echo '</ul>';

}

echo '</div><!-- .content -->';
echo '<div class="clear"></div>';
echo '</div><!-- .site-content -->';
echo '</div> <!-- .main-wrapper -->';

get_footer();

?>