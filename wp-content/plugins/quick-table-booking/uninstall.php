<?php 

// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

// delete pages
$plugin_page_id = (int) get_option('wctb_default_page');
wp_trash_post($plugin_page_id);


// delete custom post type posts
$myplugin_cpt_args = array('post_type' => 'object-booking', 'posts_per_page' => -1);
$myplugin_cpt_posts = get_posts($myplugin_cpt_args);
foreach ($myplugin_cpt_posts as $post) {
	wp_delete_post($post->ID, false);
}

// delete option
 
$option_name = 'wctb_default_page'; 
delete_option($option_name); 
// for site options in Multisite
delete_site_option($option_name);
 


