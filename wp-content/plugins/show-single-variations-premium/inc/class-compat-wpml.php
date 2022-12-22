<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * WPML Class
 *
 * Only loads if WPML is active. Adds some helper to make sure
 * variation settings are correct based on original product
 *
 * @since 1.1.1
 */
class Iconic_WSSV_Compat_WPML {
	/**
	 * Init.
	 */
	public static function init() {
		if ( ! Iconic_WSSV_Core_Helpers::is_plugin_active( 'woocommerce-multilingual/wpml-woocommerce.php' ) ) {
			return;
		}

		add_action( 'save_post', array( __CLASS__, 'set_visibility' ), 10, 1 );
	}

	/**
	 * Save: Set visibility on save,
	 * based on original variation ID
	 *
	 * @param int $post_id
	 */
	public static function set_visibility( $post_id ) {
		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}

		$post_type = get_post_type( $post_id );

		if ( $post_type != "product_variation" ) {
			return;
		}

		$original_id = self::get_original_variation_id( $post_id );

		if ( $original_id == $post_id ) {
			return;
		}

		$visibility = get_post_meta( $original_id, '_visibility', true );

		if ( ! empty( $visibility ) ) {
			update_post_meta( $post_id, '_visibility', $visibility );
		} else {
			delete_post_meta( $post_id, '_visibility' );
		}
	}

	/**
	 * Helper: Get original variation ID
	 *
	 * If this is a translated variation,
	 * get the original ID.
	 *
	 * @param int $id
	 *
	 * @return int
	 */
	public static function get_original_variation_id( $id ) {
		$wpml_original_variation_id = get_post_meta( $id, '_wcml_duplicate_of_variation', true );

		if ( $wpml_original_variation_id ) {
			$id = $wpml_original_variation_id;
		}

		return $id;
	}
}