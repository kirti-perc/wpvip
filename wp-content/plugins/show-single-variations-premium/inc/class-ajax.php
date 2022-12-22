<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Iconic_WSSV_Ajax.
 *
 * @class	Iconic_WSSV_Ajax
 * @version  1.0.0
 * @author   Iconic
 */
class Iconic_WSSV_Ajax {

	/**
	 * Instance.
	 */
	private static $instance;

	/**
	 * Init.
	 */
	public static function init() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new Iconic_WSSV_Ajax;
			self::$instance->add_ajax_events();
		}
	}

	/**
	 * Hook in methods.
	 */
	private static function add_ajax_events() {
		$ajax_events = array(
			'get_product_count' => false,
			'process_product_visibility' => false
		);

		foreach ( $ajax_events as $ajax_event => $nopriv ) {
			add_action( 'wp_ajax_iconic_wssv_' . $ajax_event, array( __CLASS__, $ajax_event ) );

			if ( $nopriv ) {
				add_action( 'wp_ajax_nopriv_iconic_wssv_' . $ajax_event, array( __CLASS__, $ajax_event ) );
			}
		}
	}

	/**
	 * Get product count.
	 */
	public static function get_product_count( $return = false ) {
		$count = Iconic_WSSV_Index::get_product_count();

		$response = array(
			'success' => true,
			'count' => $count
		);

		wp_send_json( $response );
	}

	/**
	 * Process product visibility.
	 */
	public static function process_product_visibility() {
		global $wpdb, $jck_wssv;

		$querystr = "
			SELECT $wpdb->posts.*
			FROM $wpdb->posts
			WHERE $wpdb->posts.post_type IN( 'product', 'product_variation' )
			AND post_status NOT IN ( 'trash' )
			LIMIT %d OFFSET %d
		";

		$limit = (int) filter_input( INPUT_POST, 'iconic_wssv_limit', FILTER_SANITIZE_NUMBER_INT );
		$offset = (int) filter_input( INPUT_POST, 'iconic_wssv_offset', FILTER_SANITIZE_NUMBER_INT );

		$products = $wpdb->get_results( $wpdb->prepare( $querystr, $limit, $offset ), OBJECT );

		if ( ! empty( $products ) ) {
			foreach ( $products as $product ) {
				if ( 'product_variation' === $product->post_type ) {
					$jck_wssv->on_variation_save( $product->ID );
					Iconic_WSSV_Product_Variation::set_total_sales( $product->ID );
					Iconic_WSSV_Product_Variation::set_parent_attributes_to_variation( $product->ID );
				} else {
					Iconic_WSSV_Product::update_visibility( $product->ID );
				}
			}
		}

		wp_reset_postdata();
		wp_send_json( array( 'success' => true ) );
	}
}