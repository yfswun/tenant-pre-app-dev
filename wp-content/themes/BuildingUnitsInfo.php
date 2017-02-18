<?php

    // Get all units
    
    $taxonomy = 'building_taxonomy';
    $bldgWPTerms = get_terms($taxonomy);
    $bldgPodTerms = pods($taxonomy);
    $preQual = array();
    
    foreach ($bldgWPTerms as $building) {
        
        $bldgPodTerms->fetch($building->term_id);
        $bldg_name = $bldgPodTerms->field('building_name');

        // ***
        // *** TO DO: Use bldg_building_name ***
        // ***
        $where = 'post_title like "' . $bldg_name . '%"';

        // All these do not work
        // $where = 'building_taxonomy.building_name = "' . $bldg_name . '"';
        // $where = 'Buildings.building_name = "' . $bldg_name . '"';
        // $where = 'unit_building_name.post_title = "' . $bldg_name . '"';
        // $where = 'unit_building_name.meta_value = "' . $bldg_name . '"';
        // $where = 'building_taxonomy.meta_value = "' . $bldg_name . '"';
        // $where = 'unit_building_name.building_name = "' . $bldg_name . '"';
        // $where = 'unit_num_bedrooms = 1';
        // $where = 'building_name = "' . $bldg_name . '"';
        // $where = 'unit_building_name = "' . $bldg_name . '"';
        // $where = '`t`.`unit_building_name` = "' . $bldg_name . '"';
        // $where = 'unit_building_name.meta_value.building_name = "' . $bldg_name . '"';
        // $where = 'building_taxonomy.building_name = "' . $bldg_name . '"';
        // $where = 'building_taxonomy.meta_value.building_name = "' . $bldg_name . '"';
        // $where = 'term_id = "' . $building->term_id . '"';
        // $where = 't.term_id = "' . $building->term_id . '"';
        // $where = 'id = "' . $building->term_id . '"';
        // $where = 't.unit_building_name = "' . $bldg_name . '"';
        // $where = 't.building_name = "' . $bldg_name . '"';
        // $where = 't.term = "' . $building->slug . '"';

        $params = array(
                      'where' => $where
                    , 'orderby' => 'post_title'
                    // this does not work
                    // , 'orderby' => 'unit_num_bedrooms, unit_monthly_rent'
                 );
        $units = pods('unit_type');
        $units->find($params);

        if ($units->total() > 0) {
            
            // echo "<h1 style='text-align:center;'>$bldg_name - $units->total Units</h1>";

            while ($units->fetch()) {
                // echo '<h3>' . $units->field('post_title') . '</h3>';
                $unitMinIncome = (float) $units->field('unit_min_income');
                // echo '<table>';
                    // echo '<tr>';
                        // echo '<table>';
                            // echo '<tr>';
                                // echo '<th style="text-align:center;">Number of Bedrooms</th>';
                                // echo '<th style="text-align:center;">Monthly Rent</th>';
                                // echo '<th style="text-align:center;">Required Minimum Income</th>';
                            // echo '</tr>';
                            // echo '<tr>';
                                // echo '<td style="text-align:center;">' . $units->display('unit_num_bedrooms') . '</td>';
                                // echo '<td style="text-align:center;">' . $units->display('unit_monthly_rent') . '</td>';
                                // echo '<td style="text-align:center;">' . $units->display('unit_min_income') . '</td>';
                            // echo '</tr>';
                        // echo '</table>';
                    // echo '</tr>';
                    // echo '<tr>';
                        // echo '<table>';
                            // echo '<tr>';
                                // echo '<th colspan="9" style="text-align:center;">Required Maximum Income for Household Size</th>';
                            // echo '</tr>';
                            // echo '<tr>';
                                // for ($p = 1; $p < 10; $p++) {
                                    // echo '<th style="text-align:center;';
                                    // if ($p == $householdSize) {
                                        // echo ' color:blue;';
                                    // }
                                    // echo '">' . $p . ' People</th>';
                                // }
                            // echo '</tr>';
                            // echo '<tr>';
                                // for ($p = 1; $p < 10; $p++) {
                                    // Find max income requirement for units for specified number of household members
                                    // $peopleMaxIncomeFldName = 'unit_max_income_' . $p . '_p';
                                    // $unitPeopleMaxIncome = (float) $units->field($peopleMaxIncomeFldName);
                                    // echo '<td style="text-align:center;';
                                    // if ($unitPeopleMaxIncome > 0) {
                                        // if (   ($p == $householdSize)
                                            // && ($totalAnnualIncome >= $unitMinIncome)
                                            // && ($totalAnnualIncome <= $unitPeopleMaxIncome)) {
                                            // echo ' color:green;';
                                        // }
                                        // echo '">' . $units->display($peopleMaxIncomeFldName);
                                    // } else {
                                        // echo '">&nbsp;';
                                    // }
                                    // echo '</td>';
                                // }
                            // echo '</tr>';
                        // echo '</table>';
                    // echo '</tr>';
                // echo '</table>';

                // Check income for household size
                $householdSizeMaxIncomeFldName = 'unit_max_income_' . $householdSize . '_p';
                $unitHouseholdSizeMaxIncome = (float) $units->field($householdSizeMaxIncomeFldName);
                if (IsSet($unitHouseholdSizeMaxIncome) && ($unitHouseholdSizeMaxIncome > 0)) {
                    if (($totalAnnualIncome >= $unitMinIncome) && ($totalAnnualIncome <= $unitHouseholdSizeMaxIncome)) {
                        $preQual[] = array( 'bldgName' => $units->display('unit_building_name')
                                           , 'unitName' => $units->field('post_title')
                                           , 'numBedrooms' => (float) $units->field('unit_num_bedrooms')
                                           , 'occupancy' => $householdSize
                                           , 'monthlyRent' => (float) $units->field('unit_monthly_rent')
                                           , 'minIncome' => $unitMinIncome
                                           , 'maxIncome' => $unitHouseholdSizeMaxIncome
                                         );
                    }
                }
            }
        }
    }
?>