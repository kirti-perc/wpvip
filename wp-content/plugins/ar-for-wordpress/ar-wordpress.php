<?php
/**
 * Plugin Name: AR for WordPress
 * Plugin URI: https://augmentedrealityplugins.com
 * Description: AR for WordPress Augmented Reality plugin.
 * Version: 2.5
 * Author: Web and Print Design	
 * Author URI: https://webandprint.design
 * License:  GPL2
 * Text Domain: ar-for-wordpress
 * Domain Path: /languages
 **/
 
if (!defined('ABSPATH'))
    exit;

$ar_plugin_id='Wordpress';

add_action( 'plugins_loaded', 'ar_load_text_domain' );

function ar_load_text_domain() {
    load_plugin_textdomain( 'ar-for-wordpress', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

// Functions Load
require_once(plugin_dir_path(__FILE__). 'ar-functions.php');

// Custom Wordpress Post Type
require_once(plugin_dir_path(__FILE__) . 'ar-model-post-type.php');

// Widgets Load
require_once(plugin_dir_path(__FILE__). 'ar-widgets.php');

// Plugin Updates
$this_file = __FILE__;
$update_check = "https://augmentedrealityplugins.com/plugins/check-update-ar-for-wordpress.txt";

require_once(plugin_dir_path(__FILE__) . 'ar-updates.php');

// Create Menu
add_action('admin_menu', 'ar_wp_advance_setting_menu');

function ar_wp_advance_setting_menu() {
    add_submenu_page('edit.php?post_type=armodels', __('Settings', 'ar-for-wordpress'), __('Settings', 'ar-for-wordpress'), 'manage_options', '', 'ar_subscription_setting');
}

// Hide the featured Image Box
add_action('admin_head', 'ar_wp_advance_remove_my_meta_boxen');

function ar_wp_advance_remove_my_meta_boxen() {
    remove_meta_box('postimagediv', 'armodels', 'side');
     add_meta_box('postimagediv', __('AR Image', 'ar-for-wordpress'), 'post_thumbnail_meta_box', 'armodels', 'side', 'low');
}

// Add the custom columns to the Ar Model post type
add_filter('manage_armodels_posts_columns', 'ar_wp_advance_custom_edit_posts_columns');

function ar_wp_advance_custom_edit_posts_columns($columns) {
    unset($columns['date']);
    unset($columns['pro-image']);
    $columns['Shortcode'] = __('Shortcode', 'wordpress' );
    $ARimgSrc = esc_url(plugins_url("assets/images/chair.png", __FILE__));
    $columns['thumbs'] = '<div class="ar_tooltip"><img src="' . $ARimgSrc . '" width="15"><span class="ar_tooltip_text">'.__('AR Model', 'ar-for-wordpress' ).'</span></div>'; //name of the column 
    $columns['date'] = __('Date', 'ar-for-wordpress');
    return $columns;
}

// Add the data to the custom columns for the AR Model post type
add_action('manage_armodels_posts_custom_column', 'ar_advance_custom_armodels_column', 10, 2);

// Remove View option form listing
add_filter('post_row_actions', 'ar_wp_advance_remove_row_actions', 10, 1);

function ar_wp_advance_remove_row_actions($actions) {
    if (get_post_type() === 'armodels')
        unset($actions['view']);
    return $actions;
}

// Add links to Settings page on Plugins page
add_filter( 'plugin_action_links_ar-for-wordpress/ar-wordpress.php', 'ar_settings_link' );
function ar_settings_link( $links ) {
	$url = esc_url( add_query_arg(
		'post_type',
		'armodels',
		get_admin_url() . 'edit.php'
	) );
	$settings_link = "<a href='$url&page'>" . __( 'Settings', 'ar-for-wordpress' ) . '</a>';
	array_push($links,$settings_link);
	return $links;
}
?>