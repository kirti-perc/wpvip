<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Iconic_WSSV_Helpers.
 *
 * @class    Iconic_WSSV_Helpers
 * @version  1.0.0
 * @author   Iconic
 */
class Iconic_WSSV_Helpers {
	/**
	 * Converts a string (e.g. yes or no) to a bool.
	 *
	 * @since 3.0.0
	 *
	 * @param string $string
	 *
	 * @return bool
	 */
	public static function string_to_bool( $string ) {
		return is_bool( $string ) ? $string : ( 'yes' === $string || 1 === $string || 'true' === $string || '1' === $string );
	}

	/**
	 * Get allowed HTML for title fields.
	 *
	 * @return array
	 */
	public static function wp_kses_allowed_html_title() {
		$allowed_html = wp_kses_allowed_html();
		$allowed_html['br'] = array();
		$allowed_html['span'] = array();

		return $allowed_html;
	}

	/**
	 * Is query for products and variations?
	 *
	 * @param string $where The WHERE clause of the query.
	 *
	 * @return bool
	 */
	public static function query_has_products_and_variations( $where ) {
		/**
		 * This RegEx tries to match the pattern `post_type in ( 'product', 'product_variation' )`
		 *
		 * +      - one or more
		 * *      - 0 or more
		 * \s     - space
		 * \r     - carriage return (Enter)
		 * [\s\S] - any character
		 */
		$post_type_in_regex = "post_type[\s\r]+IN[\s\r]+\([\s\r]*['|\"](product|product_variation)['|\"],[\s\S]*['|\"](product|product_variation)['|\"][\s\r]*\)";
		/**
		 * This RegEx tries to match the pattern `post_type = 'product' post_type = 'product_variation'`
		 */
		$post_type_equals_regex = "post_type[\s\r]*=[\s\r]*['|\"](product|product_variation)['|\"][\s\S]*post_type[\s\r]*=[\s\r]*['|\"](product|product_variation)['|\"]";

		preg_match( "/{$post_type_in_regex}|{$post_type_equals_regex}/", $where, $matches );

		if ( ! $matches ) {
			return false;
		}

		// Remove the first element that contains the text that matched the full pattern.
		array_shift( $matches );

		$post_types = array_filter( $matches );

		return in_array( 'product', $post_types, true ) && in_array( 'product_variation', $post_types, true );
	}
}
