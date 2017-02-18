<?php

/*
Created:     10/09/15
Updated:     08/18/16
Description: Get the current Ninja Form submission, and evaluate which units the applicant is pre-qualified for.
Author:      Sylvia Wun
*/

function setResultConstants() {
	
	$resultConst["msgSuccess"] = '<b>Congratulations!</b> Per the information that you have entered, you have qualified for the following'
								. ' apartment floor plans.<br />Please select all floor plans you are interested in and follow the forthcoming steps.';

	$resultConst["msgOverQual"] = '<b>Sorry.</b> Unfortunately you are over-qualified for affordable housing.';
	$resultConst["msgUnderQual"] = '<b>Sorry.</b> Unfortunately you are under-qualified for affordable housing.';
	$resultConst["msgViewLimits"] = '<a target="_blank" href="' . esc_url( LIMITS_URL ) . '">View Income Limits</a>';

	$resultConst["msgApply"] = 'If you are interested in applying for any of the pre-qualified rental units, please download and fill out the '
								. '<a target="_blank" href="' . esc_url( PROP_APP_GDOC_URL ) . '">property application</a>. Completed and signed '
								. 'applications must either be mailed to or dropped off at our <a target="_blank" href="' . esc_url( HQ_GMAPS_URL )
								. '">San Francisco headquarters</a>.';

	$contactUsURL = home_url() . '/contact-us/';
	
	$resultConst["msgFurther"] = 'For further assistance, please <a href="' .  esc_url( $contactUsURL ) . '" target="_blank">contact us</a>.';
	
	return $resultConst;
}

function get_applicant_info( $sub ) {
	$applicantInfo["FirstName"] = $sub->get_field( 6 );
	$applicantInfo["LastName"] = $sub->get_field( 7 );
	$applicantInfo["Email"] = $sub->get_field( 14 );
	$applicantInfo["Phone"] = $sub->get_field( 15 );
	$applicantInfo["HouseholdSize"] = ( int ) $sub->get_field( 24 );
	$applicantInfo["AnnualGrossIncome"] = ( float ) $sub->get_field( 25 );
	$applicantInfo["AnnualAssetAmt"] = ( float ) $sub->get_field( 27 );
	$applicantInfo["TotalAnnualIncome"] = ( float ) $sub->get_field( 29 );
	return $applicantInfo;
}

function get_preapp_submissions() {

	// Current Ninja Forms submission
	global $ninja_forms_processing;
	$OverQual = false;
	$UnderQual = false;
	$sub_id = $ninja_forms_processing->get_form_setting( 'sub_id' );
	$sub = Ninja_Forms()->sub( $sub_id );

	$applicantInfo = get_applicant_info( $sub );

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
											'sub_id' => $sub_id,
											'pre_app_unit_slug' => $PreAppUnitSlug
										) 
									);
					} else {
						if ( $applicantInfo["TotalAnnualIncome"] < $unitMinIncome ) {
							$UnderQual = true;
						} elseif ( $applicantInfo["TotalAnnualIncome"] > $unitHouseholdSizeMaxIncome ) {
							$OverQual = true;
						}
					}
				}
			}
		}
	}
	
	// Output

	setlocale( LC_MONETARY, 'en_US.UTF-8' );

	// $resultConst does not need HTML escaping since it is hardcoded in this PHP.
	$resultConst = setResultConstants();
	
	echo '<div class="TPASubForm">';
		echo '<p class="TPASubForm msg">';
			if ( ! empty( $preQualUnits ) ) {
				echo __( $resultConst["msgSuccess"], THEME );
			} else {
				if ( $UnderQual ) {
					echo __( $resultConst["msgUnderQual"], THEME );
				} elseif ( $OverQual ) {
					echo __( $resultConst["msgOverQual"], THEME );
				}
				echo '<span id="view_limits">' . __( $resultConst['msgViewLimits'], THEME ) . '</span>';
			}
		echo '</p>';
	echo '</div>';

	echo '<h2 class="TPASubForm">Applicant Contact</h2>';
	echo '<div class="TPATableWrapper contact">';
		echo '<table class="TPASubForm">';
			echo '<thead class="TPASubForm">';
				echo '<tr class="TPASubForm">';
					echo '<th class="TPASubForm TPACol4">First Name</th>';
					echo '<th class="TPASubForm TPACol4">Last Name</th>';
					echo '<th class="TPASubForm TPACol4">Email Address</th>';
					echo '<th class="TPASubForm TPACol4">Phone Number</th>';
				echo '</tr>';
			echo '</thead>';
			echo '<tbody class="TPASubForm">';
				echo '<tr class="TPASubForm">';
					echo '<td class="TPASubForm TPACol4 AlignCenter" id="first_name">' . esc_html( $applicantInfo["FirstName"] ) . '</td>';
					echo '<td class="TPASubForm TPACol4 AlignCenter" id="last_name">' . esc_html( $applicantInfo["LastName"] ) . '</td>';
					echo '<td class="TPASubForm TPACol4 AlignCenter" id="email_add">' . esc_html( $applicantInfo["Email"] ) . '</td>';
					// Display &nbsp; for empty value so that cell is not empty -> no height
					if ( ( $applicantInfo["Phone"] == '' ) || ( is_null( $applicantInfo["Phone"] ) ) ) {
						$phone = '&nbsp;';
					} else {
						$phone = esc_html( $applicantInfo["Phone"] );
					}
					echo '<td class="TPASubForm TPACol4 AlignCenter" id="phone_no">' . $phone . '</td>';
				echo '</tr>';
			echo '</tbody>';
		echo '</table>';
	echo '</div>';

	echo '<h2 class="TPASubForm">Household Information</h2>';
	echo '<div class="TPATableWrapper household">';
		echo '<table class="TPASubForm">';
			echo '<thead class="TPASubForm">';
				echo '<tr class="TPASubForm">';
					echo '<th class="TPASubForm TPACol4">Number of Household Members</th>';
					echo '<th class="TPASubForm TPACol4">Annual Gross Income</th>';
					echo '<th class="TPASubForm TPACol4">Annual Asset Amount</th>';
					echo '<th class="TPASubForm TPACol4">Total Annual Income</th>';
				echo '</tr>';
			echo '</thead>';
			echo '<tbody class="TPASubForm">';
				echo '<tr class="TPASubForm">';
					echo '<td class="TPASubForm TPACol4 AlignCenter">' . esc_html( $applicantInfo["HouseholdSize"] ) . '</td>';
					echo '<td class="TPASubForm TPACol4 AlignCenter">' . esc_html( '$ ' . number_format( $applicantInfo["AnnualGrossIncome"], 2 ) ) . '</td>';
					echo '<td class="TPASubForm TPACol4 AlignCenter">' . esc_html( '$ ' . number_format( $applicantInfo["AnnualAssetAmt"], 2 ) ) . '</td>';
					echo '<td class="TPASubForm TPACol4 AlignCenter">' . esc_html( '$ ' . number_format( $applicantInfo["TotalAnnualIncome"], 2 ) ) . '</td>';
				echo '</tr>';
			echo '</tbody>';
		echo '</table>';
	echo '</div>';


	if ( ! empty( $preQualUnits ) ) {
		echo '<h2 class="TPASubForm">Your Pre-Qualified Units</h2>';
		echo '<div class="TPATableWrapper units">';
			echo '<table class="TPASubForm">';
				echo '<thead class="TPASubForm">';
					echo '<tr class="TPASubForm">';
						echo '<th class="TPASubForm TPACol7">Building Name</th>';
						echo '<th class="TPASubForm TPACol7">Unit Name</th>';
						echo '<th class="TPASubForm TPACol7">Number of Bedrooms</th>';
						echo '<th class="TPASubForm TPACol7">Occupancy</th>';
						echo '<th class="TPASubForm TPACol7">Monthly Rent</th>';
						echo '<th class="TPASubForm TPACol7">Required<br />Minimum Income</th>';
						echo '<th class="TPASubForm TPACol7">Required<br />Maximum Income</th>';
					echo '</tr>';
				echo '</thead>';
				echo '<tbody class="TPASubForm">';
				foreach ( $preQualUnits as $pq ) {
					echo '<tr class="TPASubForm">';
						echo '<td class="TPASubForm TPACol7 AlignCenter"><a href="' . esc_url( $pq['bldgURL'] ) . '" target="_blank">'
								. esc_html( $pq['bldgName'] ) . '</a></td>';
						echo '<td class="TPASubForm TPACol7 AlignCenter" style="white-space:nowrap;">' . esc_html( $pq['unitName'] ) . '</td>';
						echo '<td class="TPASubForm TPACol7 AlignCenter">' . esc_html( $pq['numBedrooms'] ) . '</td>';
						echo '<td class="TPASubForm TPACol7 AlignCenter">' . esc_html( $pq['occupancy'] ) . '</td>';
						echo '<td class="TPASubForm TPACol7 AlignCenter">' . esc_html( '$ ' . number_format( $pq['monthlyRent'], 2 ) ) . '</td>';
						echo '<td class="TPASubForm TPACol7 AlignCenter">' . esc_html( '$ ' . number_format( $pq['minIncome'], 2 ) ) . '</td>';
						echo '<td class="TPASubForm TPACol7 AlignCenter">' . esc_html( '$ ' . number_format( $pq['maxIncome'], 2 ) ) . '</td>';
					echo '</tr>';
				}
				echo '</tbody>';
			echo '</table>';
		echo '</div>';
		echo '<div class="TPASubForm">';
			echo '<p class="TPASubForm">' . __( $resultConst["msgApply"], THEME ) . '</p>';
		echo '</div>';
	}

	echo '<div class="TPASubForm">';
		echo '<p class="TPASubForm">' . __( $resultConst["msgFurther"], THEME ) . '</p>';
	echo '</div>';

	// Do we need this ?????
	echo '<span style="display:none">';
		echo '<span id="email" data-email="' . antispambot( sanitize_email( $applicantInfo["Email"] ), 0 ) . '"></span>&nbsp;';
		echo '<span id="pre-app-sub" data-sub-id="' . esc_html( $sub_id ) . '"></span>';
	echo '</span>';

	// Display report date/time
	date_default_timezone_set('America/Los_Angeles');
	echo '<div class="TPASubForm clearfix" style="font-size:0.75em;">';
		echo '<p class="TPASubForm" id="SubmissionDT">Submission Date/Time: ' . date( 'Y-m-d h:i:s a' ) . '</p>';
	echo '</div>';
}
?>