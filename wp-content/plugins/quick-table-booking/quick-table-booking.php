<?php
/*
 * Plugin Name: Quick Table Booking
 * Description: Plugin that book a table on specific date and time 
 * Version: 1.0
 * Author: Perception System PVT LTD
 * Author URI: https://perceptionsystem.com/
 * Text Domain: quick-table-booking
*/
defined( 'ABSPATH' ) || die();
include_once(ABSPATH . 'wp-includes/pluggable.php');
	
if ( ! defined( 'QUICK_TABLE_BOOKING_DOMAIN' ) ) {
	define( 'QUICK_TABLE_BOOKING_DOMAIN', 'quick-table-booking' );
}

if ( ! defined( 'QUICK_TABLE_BOOKING_URL' ) ) {
	define( 'QUICK_TABLE_BOOKING_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'QUICK_TABLE_BOOKING_DIR_PATH' ) ) {
	define( 'QUICK_TABLE_BOOKING_DIR_PATH', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'QUICK_TABLE_BOOKING_BASE_NAME' ) ) {
	define( 'QUICK_TABLE_BOOKING_BASE_NAME', plugin_basename( __FILE__ ) );
}

function pstb_active_quick_table_booking(){
    global $wpdb;
    ob_start();
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );  
    
}
register_activation_hook( __FILE__, 'pstb_active_quick_table_booking' );
function pstb_quick_table_booking_deactivation() {    
    flush_rewrite_rules(); 
}
register_deactivation_hook( __FILE__, 'pstb_quick_table_booking_deactivation' );

require QUICK_TABLE_BOOKING_DIR_PATH . '/inc/wctb-function.php';
require QUICK_TABLE_BOOKING_DIR_PATH . '/inc/object-booking-metabox.php';
require QUICK_TABLE_BOOKING_DIR_PATH . '/inc/wctb-settings.php';
require QUICK_TABLE_BOOKING_DIR_PATH . '/inc/wctb-ajax.php';

if(get_option('pstb_functionality')=='pstb_table_booking_opt' || get_option('pstb_functionality')=='pstb_hotel_room_booking_opt' ){
    require QUICK_TABLE_BOOKING_DIR_PATH . '/inc/object-metabox.php';
    require QUICK_TABLE_BOOKING_DIR_PATH . '/inc/object-map.php';
}


