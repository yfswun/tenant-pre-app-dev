<?php

$TPA_taxonomy = 'building_taxonomy';
$TPA_bldg_obj = pods( $TPA_taxonomy, $bldg_slug );

if ( $TPA_bldg_obj->exists() ) {
	
	$TPA_bldg_address = $TPA_bldg_obj->field( 'building_address' );
	$TPA_bldg_city = $TPA_bldg_obj->field( 'building_city' );
	$TPA_bldg_state = $TPA_bldg_obj->field( 'building_state' );
	$TPA_bldg_zip = $TPA_bldg_obj->field( 'building_zip' );
	$TPA_bldg_desc = $TPA_bldg_obj->field( 'building_desc' );
	
	$TPA_bldg_amenities_fields = array(
									'building_gated',
									'building_surveillance',
									'building_elevator',
									'building_laundry',
									'building_community_room',
									'building_computer_lab',
									'building_parking',
								);

	$TPA_bldg_amenities = array();
	foreach ( $TPA_bldg_amenities_fields as $fld ) {
		$TPA_bldg_amenities[] = array(
										'name' => $fld,
										'label' => $TPA_bldg_obj->fields( $fld )[ 'label' ],
										'desc' => $TPA_bldg_obj->fields( $fld )[ 'description' ],
										'value' => $TPA_bldg_obj->field( $fld )
									);
	}
	
	$TPA_bldg_media = $TPA_bldg_obj->field( 'building_media' );

	echo '<div>';
		echo '<h2>Address</h2>';
			echo "<p>$TPA_bldg_address<br />";
			echo "$TPA_bldg_city, $TPA_bldg_state $TPA_bldg_zip";
			$google_maps_url = 'https://www.google.com/maps/place/';
			$add_str = str_replace( ' ', '+', htmlentities( $TPA_bldg_address, ENT_QUOTES ) );
			$city_str = str_replace( ' ', '+', htmlentities( $TPA_bldg_city ) );
			$zip_str = str_replace( ' ', '+', $TPA_bldg_zip );
			echo "<span style='padding-left:10px;'><a target='_blank' href='"
						. $google_maps_url . $add_str . ',+' . $city_str . ',+' . $TPA_bldg_state . '+' . $zip_str
						. "'>Map</a></span>";
			echo '</p>';
	echo '</div>';

	echo '<div>';
		echo '<h2>Description</h2>';
		echo "<p>$TPA_bldg_desc</p>";
	echo '</div>';

	echo '<div>';
		echo '<h2>Amenities</h2>';
		
		echo '<div class="TPATable">';
			foreach ( $TPA_bldg_amenities as $amenity) {
				echo '<div class="TPARow">';
					echo "<div class='TPACell c2-1'>" . $amenity[ 'label' ] . "</div>";
					echo "<div class='TPACell c2-2 AlignCenter'>";
						// if ( $value == 1 ) {
						if ( $amenity[ 'value' ] == 1 ) {
							echo '<i class="fa fa-check" aria-hidden="true" title="' . $amenity[ 'desc' ] . '"></i>';
						} else {
							echo '&nbsp;';
						}
					echo '</div>';
				echo '</div>';
			}
		echo '</div>';

	echo '</div>';
	
	if ( isset( $TPA_bldg_media ) && $TPA_bldg_media ) {
		echo '<h2>Photos / Video</h2>';
		echo '<div class="FloatSectionContainer">';
			foreach ( $TPA_bldg_media as $m ) {
				if ( strpos( $m['post_mime_type'], 'image' ) !== false ) {
					$img_title = trim( strip_tags( $m['post_title'] ) );
					$img_id = trim( strip_tags( $m['ID'] ) );
					$img_alt = get_post_meta( $img_id, '_wp_attachment_image_alt', true );
					$img_caption = trim( strip_tags( $m['post_excerpt'] ) );
					$img_src = wp_get_attachment_image_src( $img_id, array( 400, 600 ), false )[0];
					$thumbnail = pods_image( 
						$m,
						'thumbnail',
						0,
						array(
							'class' => 'wp-image-thumb thumbnail',
							'title' => $img_title,
							'alt' => $img_alt,
						),
						true
					);
					echo '<div class="FloatSectionCell">';
					echo '<a href="' . $img_src . '" target="_blank">';
					echo '<figure style="width:120px;" class="wp-caption alignnone">';
					echo $thumbnail;
					echo '<figcaption class="wp-caption-text" width="120">' . $img_caption . '</figcaption>';
					echo '</figure>';
					echo '</a>';
					echo '</div> <!-- FloatSectionCell -->';
				}
			}
		echo '</div> <!-- FloatSectionContainer -->';
		echo "<div class='FloatSectionClear'></div>";
	}
}
?>