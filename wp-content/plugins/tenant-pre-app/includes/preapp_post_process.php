<?php

/*
Created:     02/17/17
Updated:     02/17/17
Description: Ninja Forms 3
             (1) Get the current Ninja Form submission.
             (2) Evaluate which units the applicant is pre-qualified for
             (3) Save to the database
Author:      Sylvia Wun
*/

/**
 * @tag my_ninja_forms_processing
 * @callback my_ninja_forms_processing_callback
 */
//add_action( 'my_ninja_forms_processing', 'my_ninja_forms_processing_callback' );

/**
 * @param $form_data array
 * @return void
 */
 /*
function my_ninja_forms_processing_callback( $form_data ){
    $form_id       = $form_data[ 'id' ];
    $form_fields   =  $form_data[ 'fields' ];
    foreach( $form_fields as $field ){
        $field_id    = $field[ 'id' ];
        $field_key   = $field[ 'key' ];
        $field_value = $field[ 'value' ];
        
        // Example Field Key comparison
        if( 'my_field' == $field[ 'key' ] ){
            // This is the field that you are looking for.
        }
    }
    $form_settings = $form_data[ 'settings' ];
    $form_title    = $form_data[ 'settings' ][ 'title' ];
}
*/

// In the UI, add a custom form action with hook tag TPA_preapp_sub_post_process.

add_action( 'ninja_forms_after_submission', 'TPA_preapp_sub_post_process' );


function TPA_get_applicant_info( $fields ) {

	foreach ( $fields as $field ) {
		if ( $field[ 'key' ] == 'first_name' ) {
			$applicantInfo["FirstName"] = $field[ 'value' ];
		}
		if ( $field[ 'key' ] == 'last_name' ) {
			$applicantInfo["LastName"] = $field[ 'value' ];
		}
		if ( $field[ 'key' ] == 'email' ) {
			$applicantInfo["Email"] = $field[ 'value' ];
		}
		if ( $field[ 'key' ] == 'phone' ) {
			$applicantInfo["Phone"] = $field[ 'value' ];
		}
		if ( $field[ 'key' ] == 'cnt_household_members' ) {
			$applicantInfo["HouseholdSize"] = ( int ) $field[ 'value' ];
		}
		if ( $field[ 'key' ] == 'annual_gross_income' ) {
			$applicantInfo["AnnualGrossIncome"] = ( float) $field[ 'value' ];
		}
		if ( $field[ 'key' ] == 'annual_asset' ) {
			$applicantInfo["AnnualAssetAmt"] = ( float) $field[ 'value' ];
		}
		if ( $field[ 'key' ] == 'hidden_calc_total_annual_income' ) {
			$applicantInfo["TotalAnnualIncome"] = ( float) $field[ 'value' ];
		}
	}

	return $applicantInfo;
}

function TPA_preapp_sub_post_process( $form_data ) {

	$OverQual = false;
	$UnderQual = false;
	$form_id = $form_data[ 'id' ];
	
	// The form must be Tenant Pre-Application Form
	if ( $form_id == 5 ) {

		$form_fields =  $form_data[ 'fields' ];
		$applicantInfo = TPA_get_applicant_info( $form_fields );

		$preQualUnits = array();

		// Get all units
		
		$taxonomy = 'building_taxonomy';
		$bldgWPTerms = get_terms( $taxonomy );
		$bldgPodTerms = pods( $taxonomy );
		
		foreach ( $bldgWPTerms as $building ) {
			
			$bldgPodTerms->fetch( $building->term_id );
			$bldgSlug = $building->slug;

			$bldgName = $bldgPodTerms->field( 'building_name' );
			$bldgParentMenu = 'properties';
			$bldgURL = site_url( "/$bldgParentMenu/$bldgSlug/" );

			// Get the units in the building

			// ***
			// *** TO DO: Use bldg_building_name ***
			// ***
			$where = 'post_title like "' . $bldgName . '%"';

			// All these do not work
			// $where = 'building_taxonomy.building_name = "' . $bldgName . '"';
			// $where = 'Buildings.building_name = "' . $bldgName . '"';
			// $where = 'unit_building_name.post_title = "' . $bldgName . '"';
			// $where = 'unit_building_name.meta_value = "' . $bldgName . '"';
			// $where = 'building_taxonomy.meta_value = "' . $bldgName . '"';
			// $where = 'unit_building_name.building_name = "' . $bldgName . '"';
			// $where = 'unit_num_bedrooms = 1';
			// $where = 'building_name = "' . $bldgName . '"';
			// $where = 'unit_building_name = "' . $bldgName . '"';
			// $where = '`t`.`unit_building_name` = "' . $bldgName . '"';
			// $where = 'unit_building_name.meta_value.building_name = "' . $bldgName . '"';
			// $where = 'building_taxonomy.building_name = "' . $bldgName . '"';
			// $where = 'building_taxonomy.meta_value.building_name = "' . $bldgName . '"';
			// $where = 'term_id = "' . $building->term_id . '"';
			// $where = 't.term_id = "' . $building->term_id . '"';
			// $where = 'id = "' . $building->term_id . '"';
			// $where = 't.unit_building_name = "' . $bldgName . '"';
			// $where = 't.building_name = "' . $bldgName . '"';
			// $where = 't.term = "' . $building->slug . '"';

			$params = array(
						  'where' => $where
						, 'orderby' => 'post_title'
						// this does not work
						// , 'orderby' => 'unit_num_bedrooms, unit_monthly_rent'
					 );
			$units = pods( 'unit_type' );
			$units->find( $params );

			if ( $units->total() > 0 ) {

				while ( $units->fetch() ) {

					$unitMinIncome = ( float ) $units->field( 'unit_min_income' );
					
					// Check income for household size
					$householdSizeMaxIncomeFldName = 'unit_max_income_' . $applicantInfo["HouseholdSize"] . '_p';
					$unitHouseholdSizeMaxIncome = ( float ) $units->field( $householdSizeMaxIncomeFldName );
					if ( isset( $unitHouseholdSizeMaxIncome ) && ( $unitHouseholdSizeMaxIncome > 0 ) ) {
						if (   ( $applicantInfo["TotalAnnualIncome"] >= $unitMinIncome )
							&& ( $applicantInfo["TotalAnnualIncome"] <= $unitHouseholdSizeMaxIncome ) ) {
							// pre-qualified unit info
							$unitName = str_ireplace( $bldgName . ' - ', '', $units->field( 'post_title' ) );
							$preQualUnits[] = array(
											'bldgName' => $bldgName,
											'bldgURL' => $bldgURL,
											'unitName' => $unitName,
											'numBedrooms' => ( float ) $units->field( 'unit_num_bedrooms' ),
											'occupancy' => $applicantInfo["HouseholdSize"],
											'monthlyRent' => ( float ) $units->field( 'unit_monthly_rent' ),
											'minIncome' => $unitMinIncome,
											'maxIncome' => $unitHouseholdSizeMaxIncome,
										);
										
							// Save pre-qualified unit slugs for the submission in the custom table
							$PreAppUnitSlug = $units->field( 'post_name' );
							global $wpdb;
							$tblName = $wpdb->prefix . "pre_app_units";
							$wpdb->insert($tblName, 
											array(
												'sub_id' => $form_id,
												'pre_app_unit_slug' => $PreAppUnitSlug
											) 
										);
						} else {
							global $wpdb;
							$tblName = $wpdb->prefix . "pre_app_sub_fails";
							if ( $applicantInfo["TotalAnnualIncome"] < $unitMinIncome ) {
								$wpdb->insert($tblName, 
												array(
													'sub_id' => $form_id,
													'under_qualified' => 1
												) 
											);
							} elseif ( $applicantInfo["TotalAnnualIncome"] > $unitHouseholdSizeMaxIncome ) {
								$wpdb->insert($tblName, 
												array(
													'sub_id' => $form_id,
													'over_qualified' => 1
												) 
											);
							}
						}
					}
				}
			}
		}
	}
}
?>