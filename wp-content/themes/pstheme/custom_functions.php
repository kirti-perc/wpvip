<?php
add_filter( 'bulk_actions-users', 'misha_my_bulk_actions' );
function misha_my_bulk_actions( $bulk_array ) {
	$bulk_array['is_blocked'] = 'Blocked';
	$bulk_array['ub_blocked'] = 'Un Blocked';
	return $bulk_array;
}

add_filter( 'handle_bulk_actions-users', 'misha_bulk_action_handler', 10, 3 );
function misha_bulk_action_handler( $redirect, $doaction, $object_ids ) {
	$redirect = remove_query_arg( array( 'is_blocked_done', 'is_blocked_changed' ), $redirect );
 
	if ( $doaction == 'is_blocked' ) {
		foreach ( $object_ids as $post_id ) {
			update_user_meta($post_id,'is_blocked','on');
		}
 
		$redirect = add_query_arg(
			'is_blocked_done',
			count( $object_ids ),
		$redirect );
 
	}

	if ( $doaction == 'ub_blocked' ) {
		foreach ( $object_ids as $post_id ) {
			update_user_meta($post_id,'is_blocked','');
		}

		$redirect = add_query_arg(
			'is_blocked_done',
			count( $object_ids ),
		$redirect );
 
	}
	return $redirect;
 
}

add_action( 'admin_notices', 'misha_bulk_action_notices' );
function misha_bulk_action_notices() {
	if ( ! empty( $_REQUEST['is_blocked_done'] ) ) {
		echo '<div id="message" class="updated notice is-dismissible">
			<p>Users updated.</p>
		</div>';
	}
}
