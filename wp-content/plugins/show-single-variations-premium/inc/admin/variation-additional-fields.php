<div class="iconic-wssv-display-options">

	<strong><?php _e( 'Display Options', 'iconic-wssv' ); ?></strong>

	<div class="form-row form-row-full">
		<?php woocommerce_wp_text_input( array(
			'id'    => "jck_wssv_display_title[$loop]",
			'label' => __( 'Title', 'iconic-wssv' ),
			'type'  => 'text',
			'value' => get_post_meta( $variation->ID, '_jck_wssv_display_title', true ),
		) ); ?>
	</div>

</div>