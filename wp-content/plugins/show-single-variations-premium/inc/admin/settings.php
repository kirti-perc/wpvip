<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_filter( 'wpsf_register_settings_iconic_wssv', 'iconic_wssv_settings' );

/**
 * WooCommerce Show Single variations Settings
 *
 * @param array $wpsf_settings
 *
 * @return array
 */
function iconic_wssv_settings( $wpsf_settings ) {
	$wpsf_settings['tabs']     = isset( $wpsf_settings['tabs'] ) ? $wpsf_settings['tabs'] : array();
	$wpsf_settings['sections'] = isset( $wpsf_settings['sections'] ) ? $wpsf_settings['sections'] : array();

	$wpsf_settings['tabs'][] = array(
		'id'    => 'general',
		'title' => __( 'General', 'iconic-wssv' ),
	);

	// General.
	$wpsf_settings['sections']['variation_settings'] = array(
		'tab_id'              => 'general',
		'section_id'          => 'variation_settings',
		'section_title'       => __( 'Variation Settings', 'iconic-wssv' ),
		'section_description' => '',
		'section_order'       => 20,
		'fields'              => array(
			array(
				'id'       => 'title_format',
				'title'    => __( 'Variation Title Format', 'iconic-wssv' ),
				'subtitle' => __( 'Determines how your variation titles are formatted by default. You can also set a custom title on a per-variation basis in the variation edit screen.', 'iconic-wssv' ),
				'type'     => 'select',
				'default'  => 'parent',
				'choices'  => array(
					'parent'    => __( 'Inherit parent title', 'iconic-wssv' ),
					'attribute' => __( 'Append variation attributes', 'iconic-wssv' ),
				),
			),
		),
	);

	$wpsf_settings['sections']['advanced'] = array(
		'tab_id'              => 'general',
		'section_id'          => 'advanced',
		'section_title'       => __( 'Advanced Settings', 'iconic-wssv' ),
		'section_description' => '',
		'section_order'       => 20,
		'fields'              => array(
			array(
				'id'       => 'add_to_all_queries',
				'title'    => __( 'Add Variations To All Product Queries', 'iconic-wssv' ),
				'subtitle' => __( 'Automatically add configured variations to all product query instances in the frontend, including all custom WP_Query instances.', 'iconic-wssv' ),
				'type'     => 'checkbox',
				'default'  => '',
			),
		),
	);

	return $wpsf_settings;
}
