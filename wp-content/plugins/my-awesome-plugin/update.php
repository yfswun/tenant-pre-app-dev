<?php

/**
 * Used by my_awesome_plugin
 *
 * Run the incremental updates one by one.
 *
 * For example, if the current DB version is 3, and the target DB version is 6,
 * this function will execute update routines if they exist:
 *  - solis_update_routine_4()
 *  - solis_update_routine_5()
 *  - solis_update_routine_6()
 */
 
function solis_update() {

    // no PHP timeout for running updates
    set_time_limit( 0 );

    global $my_awesome_plugin;

    // this is the current database schema version number
    $current_db_ver = get_option( 'solis_db_ver' );

    // this is the target version that we need to reach
    $target_db_ver = My_Awesome_Plugin::DB_VER;

    // run update routines one by one until the current version number
    // reaches the target version number
    while ( $current_db_ver < $target_db_ver ) {
        // increment the current db_ver by one
        $current_db_ver ++;

        // each db version will require a separate update function
        // for example, for db_ver 3, the function name should be solis_update_routine_3
        $func = "solis_update_routine_{$current_db_ver}";
        if ( function_exists( $func ) ) {
            call_user_func( $func );
        }

        // update the option in the database, so that this process can always
        // pick up where it left off
        update_option( 'solis_db_ver', $current_db_ver );
    }
}

/**
 * I consumed 22 cups of coffee for the 1.0 release.
 */
function solis_update_routine_2() {
    $coffee_consumed = get_option( 'solis_coffee_consumed' );
    update_option( 'solis_coffee_consumed', $coffee_consumed + 22 );
}


/**
 * I'm also fond of beers and would like to tally this as well.
 */
function solis_update_routine_3() {
    $coffee_consumed = get_option( 'solis_coffee_consumed' );
    $beer_consumed = 44;

    // update the option for beer and coffee
    update_option( 'solis_beverage_consumed', $coffee_consumed + $beer_consumed );
}
