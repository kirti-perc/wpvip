<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * @class    Iconic_WSSV_Index
 * @version  1.0.0
 * @author   Iconic
 */
class Iconic_WSSV_Index {
	/**
	 * Run.
	 */
	public static function run() {
		add_filter( 'wpsf_register_settings_iconic_wssv', array( __CLASS__, 'settings' ), 20 );
		add_action( 'wpsf_after_settings_iconic_wssv', array( __CLASS__, 'process_modal' ), 10 );
		add_filter( 'iconic_wssv_product_visibility', array( __CLASS__, 'product_visibility' ), 10, 2 );
		add_filter( 'iconic_wssv_database_tables', array( __CLASS__, 'add_database_table' ) );
		add_action( 'iconic_wssv_product_processed', array( __CLASS__, 'index_product' ), 10 );
	}

	/**
	 * Add index table.
	 *
	 * @param array $tables
	 *
	 * @return array
	 */
	public static function add_database_table( $tables = array() ) {
		$tables['index'] = array(
			'name'    => 'iconic_wssv_index',
			'version' => '1.0.0',
			'schema'  => 'CREATE TABLE `%%table_name%%` (
				`product_id` bigint(20) unsigned NOT NULL,
				PRIMARY KEY (`product_id`)
			);',
		);

		return $tables;
	}

	/**
	 * Add Indexer settings.
	 *
	 * @param array $settings
	 *
	 * @return array
	 */
	public static function settings( $settings = array() ) {
		$index_count    = self::get_unindexed_count();
		$index_notifier = $index_count ? sprintf( '<span class="iconic-wssv-count" style="display:inline-block;vertical-align:top;box-sizing:border-box;margin: 0 0 0 2px;padding:0 5px;min-width:18px;height:18px;border-radius:9px;background-color:#d63638;color:#fff;font-size:11px;line-height:1.6;text-align:center;z-index:26;">%d</span>', $index_count ) : '';

		$settings['tabs'][] = array(
			'id'    => 'index',
			'title' => sprintf( '%s %s', esc_html( __( 'Index', 'iconic-wssv' ) ), $index_notifier ),
		);

		$settings['sections']['index_tools'] = array(
			'tab_id'              => 'index',
			'section_id'          => 'tools',
			'section_title'       => __( 'Tools', 'iconic-wssv' ),
			'section_description' => '',
			'section_order'       => 10,
			'fields'              => array(
				array(
					'id'       => 'index-products',
					'title'    => __( 'Index Products', 'iconic-wssv' ),
					'subtitle' => __( 'Configure the visibility of your products and variations in bulk.', 'iconic-wssv' ),
					'type'     => 'custom',
					'output'   => self::get_index_link(),
				),
			),
		);

		return $settings;
	}

	/**
	 * Get count of products not indexed.
	 *
	 * @return int
	 */
	public static function get_unindexed_count() {
		return self::get_product_count( false );
	}

	/**
	 * Get product count.
	 *
	 * @param bool $indexed Get indexed count (true) or non-indexed count (false)
	 *
	 * @return int
	 */
	public static function get_product_count( $indexed = true ) {
		global $wpdb;

		static $return = array();

		$count_index = (int) $indexed;

		if ( isset( $return[ $count_index ] ) ) {
			return $return[ $count_index ];
		}

		if ( $indexed ) {
			$query = "
				SELECT COUNT(*) as count
				FROM $wpdb->posts
				WHERE post_type IN( 'product', 'product_variation' )
				AND post_status NOT IN ( 'trash' )
			";
		} else {
			$query = "
				SELECT COUNT(*) as count
				FROM $wpdb->posts
				LEFT JOIN {$wpdb->prefix}iconic_wssv_index ON {$wpdb->prefix}iconic_wssv_index.product_id = $wpdb->posts.ID
				WHERE post_type IN( 'product', 'product_variation' )
				AND post_status NOT IN ( 'trash' )
				AND {$wpdb->prefix}iconic_wssv_index.product_id IS NULL
			";
		}

		$count = absint( $wpdb->get_var( $query ) );

		if ( is_wp_error( $count ) || empty( $count ) ) {
			$return[ $count_index ] = 0;
		}

		$return[ $count_index ] = absint( $count );

		return $return[ $count_index ];
	}

	/**
	 * Process product visibility link
	 *
	 * @return string
	 */
	public static function get_index_link() {
		$index_count = self::get_unindexed_count();

		ob_start();
		?>
		<?php if ( $index_count ) { ?>
			<div class="iconic-wssv-index-notice" style="background:#F7D7DA;padding:10px 15px;color:#731D23;border-radius:8px;margin:0 0 20px;max-width:400px;border:1px solid #F6CAD1;">
				<p style="margin: 0;">
					<?php
					/* Translators: %d: Number of products not indexed */
					echo wp_kses_post( sprintf( __( 'There are currently <strong>%d</strong> products not indexed. Click the "<strong>Run Indexer</strong>" button to prevent product visibility issues.' ), $index_count ) );
					?>
				</p>
			</div>
		<?php } ?>
		<a href="javascript: void(0);" class="button button-secondary" data-iconic-wssv-ajax="process_product_visibility"><?php esc_html_e( 'Run Indexer', 'iconic-wssv' ); ?></a>
		<?php

		return ob_get_clean();
	}

	/**
	 * Output process modal.
	 */
	public static function process_modal() {
		?>
		<div class="process-overlay"></div>
		<div class="process process--variation-visibility">
			<div class="process__content process__content--loading">
				<h3><?php esc_html_e( 'Loading...', 'iconic-wssv' ); ?></h3>
			</div>
			<div class="process__content process__content--variation-visibility process__content--open">
				<form onsubmit="return false;" class="process__form process__form--variation-visibility">
					<h3><?php esc_html_e( 'Variation Visibility', 'iconic-wssv' ); ?></h3>

					<p><?php esc_html_e( 'This step will configure the visibility settings of all variations in your store. You can still change these settings later, on a per-variation basis.', 'iconic-wssv' ); ?></p>

					<p><strong><?php esc_html_e( 'Show all product variations in the:', 'iconic-wssv' ); ?></strong></p>

					<ul id="iconic-wssv-process-variation-visibility">
						<li>
							<label><input type="checkbox" value="on" name="jck_wssv_variable_show_catalog"> <?php esc_html_e( 'Catalog/shop pages', 'iconic-wssv' ); ?></label>
						</li>
						<li>
							<label><input type="checkbox" value="on" name="jck_wssv_variable_show_filtered"> <?php esc_html_e( 'Filtered results', 'iconic-wssv' ); ?></label>
						</li>
						<li>
							<label><input type="checkbox" value="on" name="jck_wssv_variable_show_search"> <?php esc_html_e( 'Search results', 'iconic-wssv' ); ?></label>
						</li>
					</ul>

					<div>
						<label for="iconic-wssv-process-overwrite"><input id="iconic-wssv-process-overwrite" type="checkbox" value="1" name="iconic_wssv_variation_visibility_overwrite"> <?php esc_html_e( 'Overwrite existing variation settings', 'iconic-wssv' ); ?></label>
					</div>
				</form>
				<div class="process__actions">
					<a href="javascript: void(0);" class="button button-primary" data-iconic-wssv-process-screen="variable-visibility"><?php esc_html_e( 'Next', 'iconic-wssv' ); ?></a>
					<a href="javascript: void(0);" class="button button-link process__close"><?php esc_html_e( 'Cancel', 'iconic-wssv' ); ?></a>
				</div>
			</div>
			<div class="process__content process__content--variable-visibility">
				<form onsubmit="return false;" class="process__form process__form--variable-visibility">
					<h3><?php esc_attr_e( 'Variable Product Visibility', 'iconic-wssv' ); ?></h3>

					<p><?php esc_html_e( 'Usually, you do not want the parent variable product showing up alongside the variations in the shop pages. Use these settings to configure the visibility of those variable products.', 'iconic-wssv' ); ?></p>

					<div>
						<p><strong>Set the visibility for variable products:</strong></p>
						<ul id="iconic-wssv-process-variation-visibility">
							<li>
								<label for="iconic-wssv-process-variable-visibility-keep"><input type="radio" id="iconic-wssv-process-variable-visibility-keep" name="iconic_wssv_process_variable_visibility" value="" checked> <?php esc_html_e( 'Keep unchanged', 'iconic-wssv' ); ?></label>
							</li>
							<li>
								<label for="iconic-wssv-process-variable-visibility-visible"><input type="radio" id="iconic-wssv-process-variable-visibility-visible" name="iconic_wssv_process_variable_visibility" value="visible"> <?php esc_html_e( 'Shop and search results', 'iconic-wssv' ); ?></label>
							</li>
							<li>
								<label for="iconic-wssv-process-variable-visibility-catalog"><input type="radio" id="iconic-wssv-process-variable-visibility-catalog" name="iconic_wssv_process_variable_visibility" value="catalog"> <?php esc_html_e( 'Shop only', 'iconic-wssv' ); ?></label>
							</li>
							<li>
								<label for="iconic-wssv-process-variable-visibility-search"><input type="radio" id="iconic-wssv-process-variable-visibility-search" name="iconic_wssv_process_variable_visibility" value="search"> <?php esc_html_e( 'Search results only', 'iconic-wssv' ); ?></label>
							</li>
							<li>
								<label for="iconic-wssv-process-variable-visibility-hidden"><input type="radio" id="iconic-wssv-process-variable-visibility-hidden" name="iconic_wssv_process_variable_visibility" value="hidden"> <?php esc_html_e( 'Hidden', 'iconic-wssv' ); ?></label>
							</li>
						</ul>
					</div>
				</form>
				<div class="process__actions">
					<a href="javascript: void(0);" class="button button-primary" data-iconic-wssv-process-screen="start"><?php esc_html_e( 'Submit', 'iconic-wssv' ); ?></a>
					<a href="javascript: void(0);" class="button button-link process__close"><?php esc_html_e( 'Cancel', 'iconic-wssv' ); ?></a>
				</div>
			</div>
			<div class="process__content process__content--processing">
				<h3><?php esc_html_e( 'Processing', 'iconic-wssv' ); ?>
					<span class="process__count-from"></span> <?php esc_html_e( 'to', 'iconic-wssv' ); ?>
					<span class="process__count-to"></span> <?php esc_html_e( 'of', 'iconic-wssv' ); ?>
					<span class="process__count-total"></span> <?php esc_html_e( 'items', 'iconic-wssv' ); ?>, <?php esc_html_e( 'please wait...', 'iconic-wssv' ); ?>
				</h3>
				<div class="process__loading-bar">
					<div class="process__loading-bar-fill"></div>
				</div>
			</div>
			<div class="process__content process__content--complete">
				<h3><?php esc_html_e( 'Process complete', 'iconic-wssv' ); ?></h3>
				<p>
					<span class="process__count-total"></span> <?php esc_html_e( 'items were processed.', 'iconic-wssv' ); ?>
				</p>
				<div class="process__actions">
					<a href="javascript: void(0);" class="button button-primary process__close" data-reload="true"><?php esc_html_e( 'Close', 'iconic-wssv' ); ?></a>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Change visibility for bulk process.
	 *
	 * @param string     $visibility
	 * @param WC_Product $product
	 *
	 * @return mixed
	 */
	public static function product_visibility( $visibility, $product ) {
		$action = filter_input( INPUT_POST, 'action', FILTER_SANITIZE_STRING );

		if ( 'iconic_wssv_process_product_visibility' !== $action || ! $product->is_type( 'variable' ) ) {
			return $visibility;
		}

		$new_visibility = filter_input( INPUT_POST, 'iconic_wssv_process_variable_visibility', FILTER_SANITIZE_STRING );

		if ( empty( $new_visibility ) ) {
			return $visibility;
		}

		return $new_visibility;
	}

	/**
	 * Index product.
	 *
	 * @param int $product_id
	 */
	public static function index_product( $product_id ) {
		global $wpdb;

		$wpdb->replace( $wpdb->prefix . 'iconic_wssv_index', array( 'product_id' => $product_id ) );
	}
}
