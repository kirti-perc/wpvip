<?php 

function pstb_init(){	
    /* book table page start */ 
    
    if(!get_option('wctb_default_page')) {
		
		$pages_args = array();
   
        /*create Table Booking  page */       



		$args =  array( 
			'post_type'    => 'page', 
			'post_title'    => esc_html__( 'Booking', 'quick-table-booking' ), 
			'post_name'     => 'booking', 
			'post_content'  => '[book_table_form]', 
			'post_status'   => 'publish', 
			'post_author'   => 1 
		);
		
		if ( ! function_exists( 'post_exists' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/post.php' );
		}
		
		$page_id = post_exists("booking",'','','page');

		if($page_id==0){

    		$page_id 	=  wp_insert_post($args);

    		update_post_meta( $page_id, 'booking_page', "yes" );        
    		
		}
		
        add_option( 'wctb_default_page', $page_id, '', 'yes' ); 
        
    }

    /* book table page end */ 

	/* Register post type for Booking start */

	$labels_job = array( 
		'name'               => esc_attr__( 'Booking', 'post type general name', 'quick-table-booking' ), 
		'singular_name'      => esc_attr__( 'Booking', 'post type singular name', 'quick-table-booking' ), 
		'menu_name'          => esc_attr__( 'Booking', 'admin menu', 'quick-table-booking' ), 
		'name_admin_bar'     => esc_attr__( 'Booking', 'add new on admin bar', 'quick-table-booking' ), 
		'add_new'            => esc_attr__( 'Add New', 'Booking', 'quick-table-booking' ), 
		'add_new_item'       => esc_attr__( 'Add New Booking', 'quick-table-booking' ), 
		'new_item'           => esc_attr__( 'New Booking', 'quick-table-booking' ), 
		'edit_item'          => esc_attr__( 'Edit Booking', 'quick-table-booking' ), 
		'view_item'          => esc_attr__( 'View Booking', 'quick-table-booking' ), 
		'all_items'          => esc_attr__( 'All Bookings', 'quick-table-booking' ), 
		'search_items'       => esc_attr__( 'Search Bookings', 'quick-table-booking' ), 
		'parent_item_colon'  => esc_attr__( 'Parent Bookings:', 'quick-table-booking' ), 
		'not_found'          => esc_attr__( 'No Booking found.', 'quick-table-booking' ), 
		'not_found_in_trash' => esc_attr__( 'No Booking found in Trash.', 'quick-table-booking' ) 
	); 

	$args_booking = array( 
		'labels'             => $labels_job, 
		'description'        => esc_attr__( 'Description.', 'quick-table-booking' ), 
		'public'             => true, 
		'publicly_queryable' => false, 
		'show_ui'            => true, 
		'show_in_menu'       => true, 
		'query_var'          => true, 
		'rewrite'            => array( 'slug' => 'object-booking' ), 
		'capability_type'    => 'post', 
		'has_archive'        => true, 
		'hierarchical'       => false, 
		'menu_position'      => null, 
		'supports'           => array( 'title' ) 
	); 
	register_post_type( 'object-booking', $args_booking ); 
	
	/* Register post type for Booking end */


	/* register taxonomy for booking */


	register_taxonomy(  
        'booking-cat', 
        'object-booking',
        array(  
            'hierarchical' => true,  
            'label' => 'Booking Category',  
            'query_var' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => 'booking-cat'),
			'show_in_rest'		 => true,
			'public' => true,
			'publicly_queryable' => false, 

        )  
    ); 	

	if(!term_exists( 'pstb_table_booking_cat', 'booking-cat' )){

		wp_insert_term(
		'Restaurant Table Booking', // the term 
		'booking-cat', // the taxonomy
			array(
			'description'=> 'Table Booking Category', 
			'slug' => 'pstb_table_booking_cat'		
			)
		);

    }

    if(!term_exists( 'pstb_hotel_room_booking_cat', 'booking-cat' )){

		wp_insert_term(
		'Hotel Room Booking', // the term 
		'booking-cat', // the taxonomy
			array(
			'description'=> 'Room Booking Category', 
			'slug' => 'pstb_hotel_room_booking_cat' 		
			)
		);

	}


	if(!term_exists( 'pstb_dr_appoint_booking_cat', 'booking-cat' )){

		wp_insert_term(
		'Dr’s Appointment Booking', // the term 
		'booking-cat', // the taxonomy
			array(
			'description'=> 'Dr’s Appointment Booking Category', 
			'slug' => 'pstb_dr_appoint_booking_cat'		
			)
		);

	}

	if(!term_exists( 'pstb_saloon_booking_cat', 'booking-cat' )){	

		wp_insert_term(
		'Saloon Booking Booking', // the term 
		'booking-cat', // the taxonomy
			array(
			'description'=> 'Saloon Booking Category', 
			'slug' => 'pstb_saloon_booking_cat'		
			)
		);	

    }
   

    if(get_option('pstb_functionality')=='pstb_table_booking_opt' || get_option('pstb_functionality') == 'pstb_hotel_room_booking_opt' ){

    $post_type = 'object';    	
    	

    /* Register post type for Table start */

	$labels_table = array( 
		'name'               => esc_attr__( $post_type, 'post type general name', 'quick-table-booking' ), 
		'singular_name'      => esc_attr__( $post_type, 'post type singular name', 'quick-table-booking' ), 
		'menu_name'          => esc_attr__( $post_type, 'admin menu', 'quick-table-booking' ), 
		'name_admin_bar'     => esc_attr__( $post_type, 'add new on admin bar', 'quick-table-booking' ), 
		'add_new'            => esc_attr__( 'Add New', 'Table', 'quick-table-booking' ), 
		'add_new_item'       => esc_attr__( 'Add New '.$post_type, 'quick-table-booking' ), 
		'new_item'           => esc_attr__( 'New '.$post_type, 'quick-table-booking' ), 
		'edit_item'          => esc_attr__( 'Edit '.$post_type, 'quick-table-booking' ), 
		'view_item'          => esc_attr__( 'View '.$post_type, 'quick-table-booking' ), 
		'all_items'          => esc_attr__( 'All '.$post_type, 'quick-table-booking' ), 
		'search_items'       => esc_attr__( 'Search '.$post_type, 'quick-table-booking' ), 
		'parent_item_colon'  => esc_attr__( 'Parent :'.$post_type, 'quick-table-booking' ), 
		'not_found'          => esc_attr__( 'No '.$post_type.' found.', 'quick-table-booking' ), 
		'not_found_in_trash' => esc_attr__( 'No '.$post_type.' found in Trash.', 'quick-table-booking' ) 
	); 

	$args_table = array( 
		'labels'             => $labels_table, 
		'description'        => esc_attr__( 'Description.', 'quick-table-booking' ), 
		'public'             => true, 
		'publicly_queryable' => false, 
		'show_ui'            => true, 
		'show_in_menu'       => true, 
		'query_var'          => true, 
		'rewrite'            => array( 'slug' => $post_type ), 
		'capability_type'    => 'post', 
		'has_archive'        => true, 
		'hierarchical'       => false, 
		'menu_position'      => null, 
		'supports'           => array( 'title' ) ,
		'show_in_rest'		 => true
	); 
	register_post_type( $post_type, $args_table ); 
	
	/* Register post type for Table end */

	}
	else{

		update_option('pstb_hide_till_time','yes');


	}



} 

add_action('init', 'pstb_init'); 


function pstb_load_scripts($hook) {

	wp_enqueue_style( 'jquery-ui', QUICK_TABLE_BOOKING_URL . 'css/jquery-ui.min.css' );
	wp_enqueue_style( 'jquery-ui-theme', QUICK_TABLE_BOOKING_URL . 'css/jquery-ui.theme.min.css' ); 
	wp_enqueue_style( 'pstb-front-style', QUICK_TABLE_BOOKING_URL . 'css/pstb-front.css');    
	
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'jquery-ui-core' );
	wp_enqueue_script( 'jquery-ui-datepicker' );	

	wp_enqueue_script( 'validate_js', QUICK_TABLE_BOOKING_URL.'js/jquery.validate.min.js', array('jquery'), 1.0, true);	

	wp_enqueue_script( 'cookie_js', QUICK_TABLE_BOOKING_URL.'js/jquery.cookie.min.js', array('jquery'), 1.0, true);
	
	wp_enqueue_script( 'ps_front_js', QUICK_TABLE_BOOKING_URL.'js/ps_front.js', array('jquery','jquery-ui-core','jquery-ui-datepicker','cookie_js'), 1.0, true);
		

	$pstb_holidays = get_option('pstb_holidays'); 
	$pstb_week_off = get_option('pstb_week_off'); 
	$pstb_week_off = implode(',', $pstb_week_off);	

	$localize_array = array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'pstb_holidays' => $pstb_holidays,
        'pstb_week_off' => $pstb_week_off
    );

    wp_localize_script('ps_front_js', 'pstb_localize_object', $localize_array);

}
add_action('wp_enqueue_scripts', 'pstb_load_scripts');

function pstb_admin_scripts($hook) {

	wp_enqueue_style( 'jquery-ui', QUICK_TABLE_BOOKING_URL.'css/jquery-ui.min.css' );
	wp_enqueue_style( 'jquery-ui-theme', QUICK_TABLE_BOOKING_URL.'css/jquery-ui.theme.min.css' ); 

	wp_enqueue_style( 'ps_back_css', QUICK_TABLE_BOOKING_URL.'css/pstb-back.css' ); 

	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'jquery-ui-core' );
	wp_enqueue_script( 'jquery-ui-datepicker' );

	wp_enqueue_script( 'ps_back_js', QUICK_TABLE_BOOKING_URL.'js/ps_back.js', array('jquery','jquery-ui-core','jquery-ui-datepicker'), 1.0, true);		

}
add_action('admin_enqueue_scripts', 'pstb_admin_scripts');


function pstb_order_received( $order_id ) {    
	
	if (!$order_id) return;

	$order = wc_get_order( $order_id );	
	$order_status_arr = array('cancelled','failed');
	$status = $order->get_status();
	$hide_till_time = get_option('pstb_hide_till_time');
	
	if (!in_array($status, $order_status_arr)){
	
	// Allow code execution only once
    if (!get_post_meta($order_id, 'ps_booking_action_done', true))
    {     	  	

    	if(isset($_COOKIE['tb_step1_data'])) { 

			$step1_data = array();
			parse_str($_COOKIE['tb_step1_data'], $step1_data);
			$booking_date = sanitize_text_field($step1_data['booking_date']);
			$booking_time = sanitize_text_field($step1_data['booking_time']);
			if($hide_till_time!="yes"){
				$booking_time_until = sanitize_text_field($step1_data['booking_time_until']);
			}

			
			if(get_option('pstb_functionality')=='pstb_table_booking_opt' || get_option('pstb_functionality') == 'pstb_hotel_room_booking_opt' ){

				$booking_persons = sanitize_text_field($step1_data['booking_persons']);

			}
			else{

				$booking_persons = 1;

			}



			if(isset($_COOKIE['tb_step2_data'])) {
				$step2_data = array();
				parse_str($_COOKIE['tb_step2_data'], $step2_data);	
				$billing_first_name = sanitize_text_field($step2_data['billing_first_name']);
				$billing_last_name = sanitize_text_field($step2_data['billing_last_name']);
				$billing_phone = sanitize_text_field($step2_data['billing_phone']);
				$billing_email = sanitize_text_field($step2_data['billing_email']);
				$customer_note = sanitize_text_field($step2_data['customer_note']);			
			}

			if(isset($_COOKIE['tb_table_data'])) {
				$tb_table_data = array();
				parse_str($_COOKIE['tb_table_data'], $tb_table_data);	
				$sel_table = sanitize_text_field($tb_table_data['sel_table']);
			}

		if((int)$booking_persons > 0 ){	
			
			// Create post object
			
			if($hide_till_time=="yes"){
				$post_title = 'Booking '.$booking_date.' '.$booking_time;
			}
			else{
				$post_title = 'Booking '.$booking_date.' '.$booking_time.' : '.$booking_time_until;
			}


			$my_post = array(
			'post_title'    => $post_title,
			'post_content'  => '',
			'post_status'   => 'publish',
			'post_author'   => 1,
			'post_type'     => 'object-booking'		
			);

			// Insert the post into the database
			$bid = wp_insert_post( $my_post );
			
			$cur_month = date('m');
			$cur_year = date('Y');
			$booking_code = 'BK'.$cur_month.$cur_year.$bid;	

			if($bid != 0 || !is_wp_error($bid)){				

				
				update_post_meta( $bid, 'booking_date', $booking_date );
				update_post_meta( $bid, 'booking_time', $booking_time );
				
				if($hide_till_time!="yes"){
					update_post_meta( $bid, 'booking_time_until', $booking_time_until );
				}

				update_post_meta( $bid, 'first_name', $billing_first_name );
				update_post_meta( $bid, 'last_name', $billing_last_name );
				update_post_meta( $bid, 'email', $billing_email );
				update_post_meta( $bid, 'phone', $billing_phone );
				update_post_meta( $bid, 'customer_note', $customer_note );
				update_post_meta( $bid, 'booking_status', 'confirmed' );
				update_post_meta( $bid, 'no_of_persons', $booking_persons );
				update_post_meta( $bid, 'booking_code', $booking_code );
				update_post_meta( $order_id, 'booking_id', $bid );
				update_post_meta( $bid, 'booking_table', $sel_table );	
				pstb_auto_mail_responder(1,$bid);
				pstb_auto_mail_responder(2,$bid);

			}

	    }

			/* clear cookie data */ 
			if (isset($_COOKIE['tb_step1_data'])) {
				unset($_COOKIE['tb_step1_data']); 
				setcookie('tb_step1_data', null, -1, '/'); 				
			} 
			
			if (isset($_COOKIE['tb_step2_data'])) {
				unset($_COOKIE['tb_step2_data']); 
				setcookie('tb_step2_data', null, -1, '/'); 				
			} 	

			if (isset($_COOKIE['tb_table_data'])) {
				unset($_COOKIE['tb_table_data']); 
				setcookie('tb_table_data', null, -1, '/'); 				
			} 	

			/* clear cookie data */ 
		 
		// Flag the action as done (to avoid repetitions on reload for example)
        $order->update_meta_data('ps_booking_action_done', true);
        $order->save();
		
		}
	  } 

	}

}
add_action( 'woocommerce_new_order', 'pstb_order_received', 20, 1 );

function pstb_custom_woocommerce_checkout_fields( $fields ) 
{
    $fields['order']['order_comments']['placeholder'] = esc_html__( 'Message', 'quick-table-booking' );
    $fields['order']['order_comments']['label'] = esc_html__( 'Message', 'quick-table-booking' );

    return $fields;
}
add_filter( 'woocommerce_checkout_fields', 'pstb_custom_woocommerce_checkout_fields' );

/* Booking posts columns admin side */
add_filter('manage_object-booking_posts_columns', 'pstb_tbooking_post_columns',9999999);
function pstb_tbooking_post_columns($columns)
{

    $columns = array(
        'cb' => $columns['cb'],        
        'title' => esc_html__('Booking Date Time', 'quick-table-booking'),        
        'booking_code' => esc_html__('Booking Code', 'quick-table-booking'), 
        'booking_status' => esc_html__('Booking Status', 'quick-table-booking')        
    );    

    if(get_option('pstb_functionality')=='pstb_table_booking_opt' || get_option('pstb_functionality') == 'pstb_hotel_room_booking_opt' ){    	

    	$rem_columns = 	array(
        	'no_of_persons' => esc_html__('No of Persons', 'quick-table-booking'),
        	'booking_table' => esc_html__('Booking Table', 'quick-table-booking')   
        );

    	$columns = array_merge( $columns,$rem_columns);  	

    }

    return $columns;
}

/* Booking post columns values admin side */
add_action('manage_object-booking_posts_custom_column', 'pstb_tbooking_column', 9999999, 2);
function pstb_tbooking_column($column, $post_id)
{

    if ('booking_status' === $column)
    {
        echo get_post_meta($post_id, 'booking_status', true);
    }   

    if ('booking_code' === $column)
    {
        echo get_post_meta($post_id, 'booking_code', true);
    }  

    if(get_option('pstb_functionality')=='pstb_table_booking_opt' || get_option('pstb_functionality') == 'pstb_hotel_room_booking_opt' ){

		if ('no_of_persons' === $column)
		{
			echo get_post_meta($post_id, 'no_of_persons', true);
		}   

		if ('booking_table' === $column)
		{
			if(get_post_meta($post_id, 'booking_table', true)){

				echo get_the_title(get_post_meta($post_id, 'booking_table', true));

			}

		}

    } 

}

/* Booking post sortable columns */
add_filter('manage_edit-object-booking_sortable_columns', 'pstb_tbooking_sortable_columns');
function pstb_tbooking_sortable_columns($columns)
{
    $columns['booking_status'] = 'booking_status';
    $columns['no_of_persons'] = 'no_of_persons';
  
    return $columns;
}

/* season post columns sorting order */
add_action('pre_get_posts', 'pstb_tbooking_posts_orderby');
function pstb_tbooking_posts_orderby($query)
{
    if (!is_admin() || !$query->is_main_query())
    {
        return;
    }

    if ('booking_status' === $query->get('orderby'))
    {
        $query->set('orderby', 'meta_value');
        $query->set('meta_key', 'booking_status');
    }

    if ('no_of_persons' === $query->get('orderby'))
    {
        $query->set('orderby', 'meta_value');
        $query->set('meta_key', 'no_of_persons');
    }  

}


function pstb_get_unavailable_time_slots_of_day($booking_date){

	global $wpdb;	
	//$pstb_total_person = get_option('pstb_total_person');

	$booking_time_pers = array();

	$pstb_total_person = 1;


	$meta_key = 'booking_date';
	$booking_dates = $wpdb->get_results( $wpdb->prepare( 
	    "SELECT * FROM $wpdb->postmeta WHERE meta_key = %s AND meta_value = %s",$meta_key,$booking_date) );	
	
		
	foreach($booking_dates as $date){

	$meta_key2 = 'booking_time';
	$booking_times = $wpdb->get_results( $wpdb->prepare( 
	    "SELECT * FROM $wpdb->postmeta WHERE meta_key = %s AND post_id = %s",$meta_key2,$date->post_id) );


	
	$total = 0;
		foreach($booking_times as $time){

			$meta_key3 = 'no_of_persons';
			$no_of_persons = $wpdb->get_results( $wpdb->prepare( 
			    "SELECT * FROM $wpdb->postmeta WHERE meta_key = %s AND post_id = %s",$meta_key3,$time->post_id) );

			$post_status = get_post_status ( $time->post_id ); 

			$booking_status = get_post_meta( $time->post_id,'booking_status',true );

			if($post_status == 'publish' && $booking_status == 'confirmed'){

				$booking_time_pers[$time->meta_value] = (int) $booking_time_pers[$time->meta_value] + (int) $no_of_persons[0]->meta_value;

			}				

		} 	

	}

		
	$unavailable_time_slots = array();
	foreach($booking_time_pers as $time => $prev_per){		

		//if($prev_per >= (int) $pstb_total_person){
			$unavailable_time_slots[] = $time;
		//}

	}

	return $unavailable_time_slots;

}

function pstb_auto_mail_responder($temp_id,$bid){

	$admin_email = get_option('admin_email');
	$blogname = get_option('blogname');	
	$cur_year = date('Y');

	$booking_date = get_post_meta( $bid, 'booking_date', true );
	$booking_time = get_post_meta( $bid, 'booking_time', true );
	
	if($hide_till_time!="yes"){
		$booking_time_until = get_post_meta( $bid, 'booking_time_until', true );
	}	

	$first_name = get_post_meta( $bid, 'first_name', true );
	$last_name = get_post_meta( $bid, 'last_name', true );
	$email = get_post_meta( $bid, 'email', true );
	$phone = get_post_meta( $bid, 'phone', true );
	$customer_note = get_post_meta( $bid, 'customer_note', true );
	$booking_status = get_post_meta( $bid, 'booking_status', true );
	$no_of_persons = get_post_meta( $bid, 'no_of_persons', true );
	$booking_code = get_post_meta( $bid, 'booking_code', true );
	$booking_table = (int) get_post_meta( $bid, 'booking_table', true );
	$booking_table_title = get_the_title($booking_table);
	$booking_table_section = get_post_meta( $booking_table, 'table_section', true );


	if($temp_id==1){

		$to = $email;
		$subject = esc_html__( 'Booking Details', 'quick-table-booking' );
		
	}


	if($temp_id==2){

		$to = $admin_email;
		$subject = esc_html__( 'New Booking', 'quick-table-booking' );
		
	}
	
	$body = '<tr>';
	$body .= '<td>'.esc_html__("First Name", "quick-table-booking" ).'</td>';
	$body .= '<td>'.$first_name.'</td>';
	$body .= '</tr>';

	$body .= '<tr>';
	$body .= '<td>'.esc_html__("Last Name", "quick-table-booking" ).'</td>';
	$body .= '<td>'.$last_name.'</td>';
	$body .= '</tr>';

	$body .= '<tr>';
	$body .= '<td>'.esc_html__("Booking Code", "quick-table-booking" ).'</td>';
	$body .= '<td>'.$booking_code.'</td>';
	$body .= '</tr>';

	$body .= '<tr>';
	$body .= '<td>'.esc_html__("Booking Date", "quick-table-booking" ).'</td>';
	$body .= '<td>'.$booking_date.'</td>';
	$body .= '</tr>';

	$body .= '<tr>';
	$body .= '<td>'.esc_html__("Booking Time", "quick-table-booking" ).'</td>';
	
	if($hide_till_time=="yes"){

		$body .= '<td>'.$booking_time.'</td>';

	}else{ 
		$body .= '<td>'.$booking_time.' To '.$booking_time_until.'</td>';
	}
	$body .= '</tr>';

	$body .= '<tr>';
	$body .= '<td>'.esc_html__("No of Persons", "quick-table-booking" ).'</td>';
	$body .= '<td>'.$no_of_persons.'</td>';
	$body .= '</tr>';

	$body .= '<tr>';
	$body .= '<td>'.esc_html__("Booking In", "quick-table-booking" ).'</td>';
	$body .= '<td>'.$booking_table_title.' in section '.$booking_table_section.'</td>';
	$body .= '</tr>';
	
	$body .= '<tr>';
	$body .= '<td>'.esc_html__("Email", "quick-table-booking" ).'</td>';
	$body .= '<td>'.$email.'</td>';
	$body .= '</tr>';

	$body .= '<tr>';
	$body .= '<td>'.esc_html__("Phone", "quick-table-booking" ).'</td>';
	$body .= '<td>'.$phone.'</td>';
	$body .= '</tr>';

	$body .= '<tr>';
	$body .= '<td>'.esc_html__("Message", "quick-table-booking" ).'</td>';
	$body .= '<td>'.$customer_note.'</td>';
	$body .= '</tr>';	


	$new_body = '<table width="100%" border="0" align="center" cellpadding="5" cellspacing="0" style="margin:0px auto; padding:0px;width:100%;">
		<tbody bgcolor="#fff" style="padding:0px; font-family:Arial, Helvetica, sans-serif;font-size:13px;">
			<tr bgcolor="#282828">
				<td colspan="2" align="center" style="padding:10px;color:#ccc;">'.$blogname.'</td>
			</tr>';
			
		$new_body .= $body;		
			
		$new_body .= '<tr bgcolor="#000">			
				<td colspan="2" align="center" style="padding:12px; font-size:11px; color:#ccc;"> © '.$blogname.$cur_year.', All Right Reserved</td>
			</tr>
			</tbody>
		</table>';
		$from = "'From: ".$admin_email."'"; 
		$headers[] = 	$from; 
		$headers[] = 	'Content-Type: text/html; charset=UTF-8';
		$ans = wp_mail($to, $subject, $new_body, $headers );
}

add_shortcode( 'book_table_form', 'pstb_book_table_form' );
function pstb_book_table_form( $atts ) {
    
	 /* clear cache */
	 nocache_headers();

	 $pstb_total_person = get_option('pstb_total_person'); 	
	 $pstb_start_time = get_option('pstb_start_time');
	 $pstb_end_time = get_option('pstb_end_time');
	 $hide_till_time = get_option('pstb_hide_till_time');


	 if($pstb_total_person == ''){
	 	$pstb_total_person = 50;
	 }


	 $booking_date = '';
	 $booking_time = '';
	 $booking_time_until = '';
	 $booking_persons = '';	 

	 /* get cookie data */

	 if(isset($_COOKIE['tb_step1_data'])) {
			$step1_data = array();
			parse_str($_COOKIE['tb_step1_data'], $step1_data);
			$booking_date = $step1_data['booking_date'];
			$booking_time = $step1_data['booking_time'];
			if($hide_till_time!="yes"){
			$booking_time_until = $step1_data['booking_time_until'];
			}
			$booking_persons = $step1_data['booking_persons'];
	}
     
     $html = '';
     $html .= '<div class="booking-form-wrapper">';
     $html .= '<form name="pstb_booking_form_step1" method="post" id="pstb_booking_form_step1" class="pstb-step">'; 

     $heading_title  = '';   

     if(get_option('pstb_functionality')=='pstb_table_booking_opt'){
     	$heading_title = esc_html__( 'Restaurant Table', 'quick-table-booking' );     	
     }
     else if(get_option('pstb_functionality')=='pstb_hotel_room_booking_opt'){
     	$heading_title = esc_html__( 'Hotel Room', 'quick-table-booking' );     	
     }
     else if(get_option('pstb_functionality')=='pstb_dr_appoint_booking_opt'){
     	$heading_title = esc_html__( 'Dr’s Appointment', 'quick-table-booking' );     	
     }
     else if(get_option('pstb_functionality')=='pstb_saloon_booking_opt'){
     	$heading_title = esc_html__( 'Saloon ', 'quick-table-booking' );     	
     }


     $html .= '<h3 class="tbbs-title">'.esc_html__( $heading_title.' Booking', 'quick-table-booking' ).'</h3>'; 

     do_action('pstb_booking_form_start');


    if(get_option('pstb_functionality')=='pstb_table_booking_opt' || get_option('pstb_functionality')=='pstb_hotel_room_booking_opt' ){

		$html .= '<div class="field-wrp">'; 
		$html .= '<label class="psbk-lbl" for="booking_persons">'.esc_html__( 'No. of person', 'quick-table-booking' ).'</label>';
		$html .= '<input type="number" value="'.$booking_persons.'" class="psbk-field" name="booking_persons" id="booking_persons" min="1" max="'.esc_attr($pstb_total_person).'" />';
		$html .= '</div>'; 

	}

	$html .= '<div class="field-wrp">'; 
	$html .= '<label class="psbk-lbl" for="booking_date">'.esc_html__( 'Select Date', 'quick-table-booking' ).'</label>';
	$html .= '<input type="text" class="psbk-field" name="booking_date" id="booking_date"  value="'.$booking_date.'" readonly="readonly">';
	$html .= '</div>';  

	if($hide_till_time=="yes"){
		$html .= '<div class="field-wrp" >';  
    }else{ 
    	$html .= '<div class="field-wrp psbk-half" >';  
    }
	$html .= '<label class="psbk-lbl" for="booking_persons">'.esc_html__( 'From Time', 'quick-table-booking' ).'</label>';
	$html .= '<select name="booking_time" id="booking_time" class="psbk-field psbk-dw-field" >';
	$html .= '<option value="" >'.esc_html__( 'Select', 'quick-table-booking' ).'</option>';
	//$html .= '<input type="time" name="booking_time" id="booking_time" value="" />';
	 if($pstb_start_time == ''){

	 	$pstb_start_time = "10-00 AM";

	 }

	 if($pstb_end_time == ''){

	 	$pstb_end_time = "10-00 PM";

	 }

	 $a = DateTime::createFromFormat("g-i A", $pstb_start_time);
     $a = $a->getTimestamp();

     $b = DateTime::createFromFormat("g-i A", $pstb_end_time);
     $b = $b->getTimestamp();
    
     $t=$a;
     while($t<=$b){
     	$time = date("g-i A",$t);  

     	$selected = '';
     	if($time == $booking_time){
     		$selected = 'selected=selected';
     	}

     	$html .= '<option value="'.$time.'" '.$selected.'>'.$time.'</option>';   	
     	$t = strtotime('+30 minutes',$t);
     } 	 

	 $html .= '</select>';
	 $html .= '</div>'; 	 

	if($hide_till_time != "yes"){

	$html .= '<div class="field-wrp psbk-half psbk-right-field">';  
	  $html .= '<label class="psbk-lbl" for="booking_persons">'.esc_html__( 'Till', 'quick-table-booking' ).'</label>';
     $html .= '<select name="booking_time_until" id="booking_time_until" class="psbk-field psbk-dw-field" >';
	 $html .= '<option value="" selected="selected">'.esc_html__( 'Select', 'quick-table-booking' ).'</option>';
	 
	 if($pstb_start_time != '' && $pstb_end_time != ''){

		 $a = DateTime::createFromFormat("g-i A", $pstb_start_time);
	     $a = $a->getTimestamp();

	     $b = DateTime::createFromFormat("g-i A", $pstb_end_time);
	     $b = $b->getTimestamp();
	    
	     $t=$a;
	     while($t<=$b){
	     	$time = date("g-i A",$t); 

			$selected = '';
			if($time == $booking_time_until){
				$selected = 'selected=selected';
			}

	     	$html .= '<option value="'.$time.'" '.$selected.'>'.$time.'</option>';   	
	     	$t = strtotime('+30 minutes',$t);
	     }

 	 }	

	 $html .= '</select>';
	 $html .= '</div>'; 	 

	}

	 $html .= '<div class="field-wrp step-btn-sec">'; 
	 $html .= '<input type="button" value="'.esc_html__( 'Next', 'quick-table-booking' ).'" class="psbk-btn psbk-next-btn pstb-first-step" />';
	 $html .= '</div>';     

     $html .= '</form>';




     if(get_option('pstb_functionality')=='pstb_table_booking_opt' || get_option('pstb_functionality')=='pstb_hotel_room_booking_opt' ){

     $html .= '<form name="pstb_booking_form_table_list" method="post" id="pstb_booking_form_table_list" class="pstb-step">'; 
     
     $html .= '</form>';


 	}


     $html .= '<form name="pstb_booking_form_step2" method="post" id="pstb_booking_form_step2" class="pstb-step">'; 

     $html .= '<h3 class="tbbs-title">'.esc_html__( 'Give Your Details', 'quick-table-booking' ).'</h3>';


     $billing_first_name = '';
     $billing_last_name = '';
     $billing_phone = '';
     $billing_email = '';  
     $customer_note = '';

    /* get cookie data if exist */

    if(isset($_COOKIE['tb_step2_data'])) {
		$step2_data = array();
		parse_str($_COOKIE['tb_step2_data'], $step2_data);	
		
		$billing_first_name = $step2_data['billing_first_name'];
		$billing_last_name = $step2_data['billing_last_name'];
		$billing_phone = $step2_data['billing_phone'];
		$billing_email = $step2_data['billing_email'];
		$customer_note = $step2_data['customer_note'];			
	}


     /* get  personal info from billing detail of customer if exist */

     if(is_user_logged_in()){
	     $current_user = wp_get_current_user();   

	     if ( class_exists( 'WooCommerce' ) ) {

		     if($current_user->billing_first_name != ''){
		     	$billing_first_name = $current_user->billing_first_name;
		     }
		     if($current_user->billing_last_name != ''){
		     	$billing_last_name = $current_user->billing_last_name;
		     }
		     if($current_user->billing_phone != ''){
		     	$billing_phone = $current_user->billing_phone;
		     }
		     if($current_user->billing_email != ''){
		     	$billing_email = $current_user->billing_email;
		     } 
		}
     }


	  $html .= '<div class="field-wrp psbk-half">'; 
	  $html .= '<label class="psbk-lbl" for="booking_persons">'.esc_html__( 'First Name', 'quick-table-booking' ).'</label>';
	 $html .= '<input type="text" class="psbk-field" name="billing_first_name" id="billing_first_name"  value="'.$billing_first_name.'">';
	 $html .= '</div>';

	$html .= '<div class="field-wrp psbk-half psbk-right-field">'; 
	$html .= '<label class="psbk-lbl" for="booking_persons">'.esc_html__( 'Last Name', 'quick-table-booking' ).'</label>';
	$html .= '<input type="text" class="psbk-field" name="billing_last_name" id="billing_last_name" value="'.$billing_last_name.'">';
	$html .= '</div>';

     $html .= '<div class="field-wrp psbk-half">'; 
     $html .= '<label class="psbk-lbl" for="booking_persons">'.esc_html__( 'Phone Number', 'quick-table-booking' ).'</label>';
     $html .= '<input type="text" class="psbk-field" name="billing_phone" id="billing_phone"  value="'.$billing_phone.'">';
     $html .= '</div>';

      $html .= '<div class="field-wrp psbk-half psbk-right-field">'; 
      $html .= '<label class="psbk-lbl" for="booking_persons">'.esc_html__( 'Email Address', 'quick-table-booking' ).'</label>';
     $html .= '<input type="email" class="psbk-field" name="billing_email" id="billing_email"  value="'.$billing_email.'" >';
     $html .= '</div>'; 

    if( class_exists( 'WooCommerce' ) ) { 
   
	$shop_url = get_permalink( wc_get_page_id( 'shop' ) );

		 $html .= '<div class="field-wrp psbk-half">';      
		 $html .= '<input type="radio" class="psbk-rad-btn" name="booking_option" id="without_food" value="without_food" checked="checked" />';
		 $html .= '<label for="without_food">'.esc_html__( 'Book without Product', 'quick-table-booking' ).'</label>';
	     $html .= '</div>'; 

	     $html .= '<div class="field-wrp psbk-half psbk-right-field">';      
		 $html .= '<input type="radio" class="psbk-rad-btn" name="booking_option" id="with_food" value="with_food" />';
		 $html .= '<label for="with_food">'.esc_html__( 'Book with Product', 'quick-table-booking' ).'</label>';
		 $html .= '</div>'; 

		 $html .= '<input type="hidden" name="shop_url" value="'.esc_url($shop_url).'" />';	 

	}
	else{

		$html .= '<input type="hidden" name="booking_option" id="without_food" value="without_food" />';

	}


	 $html .= '<div class="field-wrp">'; 
	  $html .= '<label class="psbk-lbl" for="booking_persons">'.esc_html__( 'Message', 'quick-table-booking' ).'</label>';
     $html .= '<textarea class="psbk-field" name="customer_note" id="customer_note"  >'.$customer_note.'</textarea>';
     $html .= '</div>'; 


     do_action('pstb_booking_form_end');

     $html .= '<div class="field-wrp step-btn-sec">'; 

	 
    if(get_option('pstb_functionality')=='pstb_table_booking_opt' || get_option('pstb_functionality')=='pstb_hotel_room_booking_opt' ){

	 $html .= '<input type="button" value="'.esc_html__( 'Prev', 'quick-table-booking' ).'" class="psbk-btn psbk-prev-btn psbk-goto-table-listing" />';
    }
    else{

    	$html .= '<input type="button" value="'.esc_html__( 'Prev', 'quick-table-booking' ).'" class="psbk-btn psbk-prev-btn psbk-goto-first" />';

    }



	 $html .= '<input type="submit" value="'.esc_html__( 'Submit', 'quick-table-booking' ).'" class="psbk-btn psbk-next-btn pstb-second-step" />';
	 
	 $html .= '</div>'; 
      
     $html .= '</form>';   

     $html .= '<div class="booking-message-box">';
     $html .= '</div>';   

     $html .= '</div>';

     return $html;
}


function pstb_check_person_availability_timeslot_day($booking_date,$booking_time,$persons){

	global $wpdb;	
	//$pstb_total_person = get_option('pstb_total_person');

	$pstb_total_person = 1;
	$persons = 1;


	$booking_time_pers = array();

	$meta_key = 'booking_date';
	$booking_dates = $wpdb->get_results( $wpdb->prepare( 
	    "SELECT * FROM $wpdb->postmeta WHERE meta_key = %s AND meta_value = %s",$meta_key,$booking_date) );	
	
		
	foreach($booking_dates as $date){

	$meta_key2 = 'booking_time';
	$booking_times = $wpdb->get_results( $wpdb->prepare( 
	    "SELECT * FROM $wpdb->postmeta WHERE meta_key = %s AND meta_value = %s AND post_id = %s",$meta_key2,$booking_time,$date->post_id) );


	
	$total = 0;
		foreach($booking_times as $time){

			$meta_key3 = 'no_of_persons';
			$no_of_persons = $wpdb->get_results( $wpdb->prepare( 
			    "SELECT * FROM $wpdb->postmeta WHERE meta_key = %s AND post_id = %s",$meta_key3,$time->post_id) );
			
			$post_status = get_post_status ( $time->post_id ); 

			$booking_status = get_post_meta( $time->post_id,'booking_status',true );

			if($post_status == 'publish' && $booking_status == 'confirmed'){

				$booking_time_pers[$time->meta_value] = (int) $booking_time_pers[$time->meta_value] + (int) $no_of_persons[0]->meta_value;	
			}			

		} 	

	}

		
	$unavailable_time_slot = false;
	
	if(!empty($booking_time_pers)){

		foreach($booking_time_pers as $time => $prev_per){
			
			$now_persons = $prev_per + $persons;

			if($now_persons > (int) $pstb_total_person){
				$unavailable_time_slot = true;
			}

		}
    }

	return $unavailable_time_slot;
	

}

function pstb_woocommerce_checkout_before_order_review() { 
		
		$hide_till_time = get_option('pstb_hide_till_time');
		
		if(isset($_COOKIE['tb_step1_data'])) {
			$step1_data = array();
			parse_str($_COOKIE['tb_step1_data'], $step1_data);
			$booking_date = $step1_data['booking_date'];
			$booking_time = $step1_data['booking_time'];
			if($hide_till_time!="yes"){
			$booking_time_until = $step1_data['booking_time_until'];
			}

			$booking_persons = '';

			if(isset($step1_data['booking_persons'])){
				$booking_persons = $step1_data['booking_persons'];
			}


			if(isset($_COOKIE['tb_step2_data'])) {
				$step2_data = array();
				parse_str($_COOKIE['tb_step2_data'], $step2_data);	
				$billing_first_name = $step2_data['billing_first_name'];
				$billing_last_name = $step2_data['billing_last_name'];
				$billing_phone = $step2_data['billing_phone'];
				$billing_email = $step2_data['billing_email'];
				$customer_note = $step2_data['customer_note'];			
			}
			if(isset($_COOKIE['tb_table_data'])) {
				$tb_table_data = array();
				parse_str($_COOKIE['tb_table_data'], $tb_table_data);	
				if(isset($tb_table_data['sel_table'])){
					$sel_table = sanitize_text_field($tb_table_data['sel_table']);
					$booking_table_title = get_the_title($sel_table);
					$booking_table_section = get_post_meta($sel_table,'table_section',true);
				}
				
				
			}		
		

			$html = '';		
			$html .= '<div class="ck-bk-details">';
			$html .= '<h6>'.esc_html__( 'Your Booking Details', 'quick-table-booking' ).'</h6>';
			$html .= '<table>';
			$html .= '<tbody>';			
			$html .= '<tr>';
			$html .= '<td>'.esc_html__( 'Booking Date', 'quick-table-booking' ).':</td><td>'.$booking_date.'</td>';
			$html .= '</tr>';
			$html .= '<tr>';
			$html .= '<td>'.esc_html__( 'Booking Time', 'quick-table-booking' ).':</td>';
			
			if($hide_till_time=="yes"){
				$html .= '<td>'.$booking_time.'</td>';
			}else{
				$html .= '<td>'.$booking_time.esc_html__( ' TO ', 'quick-table-booking' ).$booking_time_until.'</td>';
			}
			$html .= '</tr>';


			if(get_option('pstb_functionality')=='pstb_table_booking_opt' || get_option('pstb_functionality')=='pstb_hotel_room_booking_opt' ){

				$html .= '<tr>';
				$html .= '<td>'.esc_html__( 'Persons', 'quick-table-booking' ).':</td><td>'.$booking_persons.'</td>';
				$html .= '</tr>';			
				$html .= '<tr>';
				$html .= '<td>'.esc_html__( 'Booking Object', 'quick-table-booking' ).':</td><td>'.$booking_table_title.esc_html__( ' in section ', 'quick-table-booking' ).$booking_table_section.'</td>';
				$html .= '</tr>';

			}


			$html .= '<tr>';	
			$html .= '<td>'.esc_html__( 'First Name', 'quick-table-booking' ).':</td><td>'.$billing_first_name.'</td>';
			$html .= '</tr>';
			$html .= '<tr>';
			$html .= '<td>'.esc_html__( 'Last Name', 'quick-table-booking' ).':</td><td>'.$billing_last_name.'</td>';
			$html .= '</tr>';
			$html .= '<tr>';
			$html .= '<td>'.esc_html__( 'Phone', 'quick-table-booking' ).':</td><td>'.$billing_phone.'</td>';
			$html .= '</tr>';
			$html .= '<tr>';
			$html .= '<td>'.esc_html__( 'Email Address', 'quick-table-booking' ).':</td><td>'.$billing_email.'</td>';
			$html .= '</tr>';
			$html .= '<tr>';
			$html .= '<td>'.esc_html__( 'Message', 'quick-table-booking' ).':</td><td>'.$customer_note.'</td>';
			$html .= '</tr>';
			$html .= '</tbody>';
			$html .= '</table>';	
			$html .= '</div>';	
			echo $html;
			
		}


}; 
add_action( 'woocommerce_checkout_before_order_review', 'pstb_woocommerce_checkout_before_order_review', 10, 0 ); 

add_action( 'woocommerce_thankyou', 'pstb_thankyou_page_booking_details', 4 );

function pstb_thankyou_page_booking_details( $order_id ) {		
		$bid = get_post_meta( $order_id, 'booking_id', true );
 	    
 	    $hide_till_time = get_option('pstb_hide_till_time');
		

		if($bid != ''){				
			
			$booking_date = get_post_meta( $bid, 'booking_date', true  );
			$booking_time = get_post_meta( $bid, 'booking_time', true  );
			
			if($hide_till_time!="yes"){

			$booking_time_until = get_post_meta( $bid, 'booking_time_until', true  );
			}


			$first_name = get_post_meta( $bid, 'first_name', true  );
			$last_name = get_post_meta( $bid, 'last_name', true  );
			$email = get_post_meta( $bid, 'email', true  );
			$phone = get_post_meta( $bid, 'phone', true  );
			$customer_note = get_post_meta( $bid, 'customer_note',true );				
			$no_of_persons = get_post_meta( $bid, 'no_of_persons', true  );
			$booking_code = get_post_meta( $bid, 'booking_code', true  );
			$sel_table = get_post_meta( $bid, 'booking_table', true  );
			$booking_table_title = get_the_title($sel_table);
			$booking_table_section = get_post_meta($sel_table,'table_section',true);
		
			$html = '';		
			$html .= '<div class="ck-bk-details">';
			$html .= '<h2>'.esc_html__( 'Booking Details', 'quick-table-booking' ).'</h2>';
			$html .= '<table>';
			$html .= '<tbody>';	
			$html .= '<tr>';
			$html .= '<td>'.esc_html__( 'Booking Code', 'quick-table-booking' ).':</td><td>'.$booking_code.'</td>';
			$html .= '</tr>';
			$html .= '<tr>';
			$html .= '<td>'.esc_html__( 'Booking Date', 'quick-table-booking' ).':</td><td>'.$booking_date.'</td>';
			$html .= '</tr>';
			$html .= '<tr>';
			$html .= '<td>'.esc_html__( 'Booking Time', 'quick-table-booking' ).':</td>';
			if($hide_till_time=="yes"){
				$html .= '<td>'.$booking_time;
			}else{
				$html .= '<td>'.$booking_time.' To '.$booking_time_until.'</td>';
			}

			$html .= '</tr>';


			if(get_option('pstb_functionality')=='pstb_table_booking_opt' || get_option('pstb_functionality')=='pstb_hotel_room_booking_opt' ){

				$html .= '<tr>';
				$html .= '<td>'.esc_html__( 'Persons', 'quick-table-booking' ).':</td><td>'.$no_of_persons.'</td>';
				$html .= '</tr>';
				$html .= '<tr>';			
				$html .= '<td>'.esc_html__( 'Booking Object', 'quick-table-booking' ).':</td><td>'.$booking_table_title.esc_html__( ' in section ', 'quick-table-booking' ).$booking_table_section.'</td>';
				$html .= '</tr>';
			}


			$html .= '<tr>';
			$html .= '<td>'.esc_html__( 'First Name', 'quick-table-booking' ).':</td><td>'.$first_name.'</td>';
			$html .= '</tr>';
			$html .= '<tr>';
			$html .= '<td>'.esc_html__( 'Last Name', 'quick-table-booking' ).':</td><td>'.$last_name.'</td>';
			$html .= '</tr>';
			$html .= '<tr>';
			$html .= '<td>'.esc_html__( 'Phone', 'quick-table-booking' ).':</td><td>'.$phone.'</td>';
			$html .= '</tr>';
			$html .= '<tr>';
			$html .= '<td>'.esc_html__( 'Email Address', 'quick-table-booking' ).':</td><td>'.$email.'</td>';
			$html .= '</tr>';
			$html .= '<tr>';
			$html .= '<td>'.esc_html__( 'Message', 'quick-table-booking' ).':</td><td>'.$customer_note.'</td>';
			$html .= '</tr>';
			$html .= '</tbody>';
			$html .= '</table>';	
			$html .= '</div>';	
			echo $html;
		}

}


add_action( 'woocommerce_admin_order_data_after_order_details', 'pstb_order_booking_details' );

function pstb_order_booking_details( $order ){ 


   
   		$order_id = $order->get_id();
	
		$bid = get_post_meta( $order_id, 'booking_id', true );
 	    
		$hide_till_time = get_option('pstb_hide_till_time');

		if($bid != ''){				
			
			$booking_date = get_post_meta( $bid, 'booking_date', true  );
			$booking_time = get_post_meta( $bid, 'booking_time', true  );
			
			if($hide_till_time!="yes"){
			$booking_time_until = get_post_meta( $bid, 'booking_time_until', true  );
			}
			
			$first_name = get_post_meta( $bid, 'first_name', true  );
			$last_name = get_post_meta( $bid, 'last_name', true  );
			$email = get_post_meta( $bid, 'email', true  );
			$phone = get_post_meta( $bid, 'phone', true  );
			$customer_note = get_post_meta( $bid, 'customer_note',true );				
			$no_of_persons = get_post_meta( $bid, 'no_of_persons', true  );
			$booking_code = get_post_meta( $bid, 'booking_code', true  );
			$sel_table = get_post_meta( $bid, 'booking_table', true  );
			$booking_table_title = get_the_title($sel_table);
			$booking_table_section = get_post_meta($sel_table,'table_section',true);
		
			$html = '';		
			$html .= '<div class="form-field form-field-wide">';
			$html .= '<h2>'.esc_html__( 'Booking Details', 'quick-table-booking' ).'</h2>';
			$html .= '<table>';
			$html .= '<tbody>';	
			$html .= '<tr>';
			$html .= '<td>'.esc_html__( 'Booking Code', 'quick-table-booking' ).':</td><td>'.$booking_code.'</td>';
			$html .= '</tr>';
			$html .= '<tr>';
			$html .= '<td>'.esc_html__( 'Booking Date', 'quick-table-booking' ).':</td><td>'.$booking_date.'</td>';
			$html .= '</tr>';
			$html .= '<tr>';
			$html .= '<td>'.esc_html__( 'Booking Time', 'quick-table-booking' ).':</td>';

			if($hide_till_time=="yes"){
				$html .= '<td>'.$booking_time.'</td>';
			}
			else{
				$html .= '<td>'.$booking_time.' TO '.$booking_time_until.'</td>';
			}
			$html .= '</tr>';


			if(get_option('pstb_functionality')=='pstb_table_booking_opt' || get_option('pstb_functionality')=='pstb_hotel_room_booking_opt' ){

				$html .= '<tr>';
				$html .= '<td>'.esc_html__( 'Persons', 'quick-table-booking' ).':</td><td>'.$no_of_persons.'</td>';
				$html .= '</tr>';
				$html .= '<tr>';			
				$html .= '<td>'.esc_html__( 'Booking Object', 'quick-table-booking' ).':</td><td>'.$booking_table_title.esc_html__( ' in section ', 'quick-table-booking' ).$booking_table_section.'</td>';
				$html .= '</tr>';

			}


			$html .= '<tr>';
			$html .= '<td>'.esc_html__( 'First Name', 'quick-table-booking' ).':</td><td>'.$first_name.'</td>';
			$html .= '</tr>';
			$html .= '<tr>';
			$html .= '<td>'.esc_html__( 'Last Name', 'quick-table-booking' ).':</td><td>'.$last_name.'</td>';
			$html .= '</tr>';
			$html .= '<tr>';
			$html .= '<td>'.esc_html__( 'Phone', 'quick-table-booking' ).':</td><td>'.$phone.'</td>';
			$html .= '</tr>';
			$html .= '<tr>';
			$html .= '<td>'.esc_html__( 'Email Address', 'quick-table-booking' ).':</td><td>'.$email.'</td>';
			$html .= '</tr>';
			$html .= '<tr>';
			$html .= '<td>'.esc_html__( 'Message', 'quick-table-booking' ).':</td><td>'.$customer_note.'</td>';
			$html .= '</tr>';
			$html .= '</tbody>';
			$html .= '</table>';	
			$html .= '</div>';	
			echo $html;
		}   
 } 

 function pstb_sanitize_text_or_array_field($array_or_string) {
    if( is_string($array_or_string) ){
        $array_or_string = sanitize_text_field($array_or_string);
    }elseif( is_array($array_or_string) ){
        foreach ( $array_or_string as $key => &$value ) {
            if ( is_array( $value ) ) {
                $value = pstb_sanitize_text_or_array_field($value);
            }
            else {
                $value = sanitize_text_field( $value );
            }
        }
    }

    return $array_or_string;
}

function pstb_get_day($day_no=0){

	$day_text = '';

	switch($day_no){

		case 0 :
				$day_text = 'Sunday';
				break;
		case 1 :
				$day_text = 'Monday';
				break;
		case 2 :
				$day_text = 'Tuesday';
				break;
		case 3 :
				$day_text = 'Wednesday';
				break;
		case 4 :
				$day_text = 'Thursday';
				break;
		case 5 :
				$day_text = 'Friday';
				break;
		case 6 :
				$day_text = 'Saturday';
				break;
		default :
				$day_text = '';
					
	}

	return $day_text;	

}


/* Table posts columns admin side */
add_filter('manage_table_posts_columns', 'pstb_table_post_columns');
function pstb_table_post_columns($columns)
{

    $columns = array(
        'cb' => $columns['cb'],        
        'title' => esc_html__('Name', 'quick-table-booking'),
        'persons' => esc_html__('Persons', 'quick-table-booking'),
        'min_persons' => esc_html__('Min. Persons', 'quick-table-booking'),       
        'shape' => esc_html__('Shape', 'quick-table-booking'),
        'section' => esc_html__('Section', 'quick-table-booking'),
        'seq_no' => esc_html__('Seq. No', 'quick-table-booking')          
    );

    return $columns;
}

/* Table post columns values admin side */
add_action('manage_table_posts_custom_column', 'pstb_table_column', 10, 2);
function pstb_table_column($column, $post_id)
{

    if ('persons' === $column)
    {
        echo get_post_meta($post_id, 'table_persons', true);
    }

    if ('min_persons' === $column)
    {
        echo get_post_meta($post_id, 'table_persons_min', true);
    }   

    if ('shape' === $column)
    {
        echo get_post_meta($post_id, 'table_shape', true);
    }

     if ('section' === $column)
    {
        echo get_post_meta($post_id, 'table_section', true);
    }   

    if ('seq_no' === $column)
    {
        echo get_post_meta($post_id, 'table_sequence_no', true);
    }
 

}

/* Table post sortable columns */
add_filter('manage_edit-table_sortable_columns', 'pstb_table_sortable_columns');
function pstb_table_sortable_columns($columns)
{
    $columns['persons'] = 'table_persons';
    $columns['min_persons'] = 'table_persons_min';
    $columns['shape'] = 'table_shape';
    $columns['section'] = 'table_section';
    $columns['seq_no'] = 'table_sequence_no';
   
    return $columns;
}

/* Table post columns sorting order */
add_action('pre_get_posts', 'pstb_table_posts_orderby');
function pstb_table_posts_orderby($query)
{
    if (!is_admin() || !$query->is_main_query())
    {
        return;
    }

    if ('table_persons' === $query->get('orderby'))
    {
        $query->set('orderby', 'meta_value_num');
        $query->set('meta_key', 'table_persons');
    }

    if ('table_persons_min' === $query->get('orderby'))
    {
        $query->set('orderby', 'meta_value_num');
        $query->set('meta_key', 'table_persons_min');
    }  

     if ('table_shape' === $query->get('orderby'))
    {
        $query->set('orderby', 'meta_value');
        $query->set('meta_key', 'table_shape');
    }  

     if ('table_section' === $query->get('orderby'))
    {
        $query->set('orderby', 'meta_value');
        $query->set('meta_key', 'table_section');
    }  

     if ('table_sequence_no' === $query->get('orderby'))
    {
        $query->set('orderby', 'meta_value_num');
        $query->set('meta_key', 'table_sequence_no');
    }    

}


function pstb_check_table_availability_timeslot_day($booking_date,$booking_time,$persons,$table_id){

	global $wpdb;	
	//$pstb_total_person = get_option('pstb_total_person');
	$pstb_total_person = get_post_meta($table_id,'table_persons',true);

	$booking_time_pers = array();

	$meta_key = 'booking_table';
	$bookings = $wpdb->get_results( $wpdb->prepare( 
	    "SELECT * FROM $wpdb->postmeta WHERE meta_key = %s AND meta_value = %s",$meta_key,$table_id) );	

	foreach($bookings as $booking){

	$meta_key = 'booking_date';
	$booking_dates = $wpdb->get_results( $wpdb->prepare( 
	    "SELECT * FROM $wpdb->postmeta WHERE meta_key = %s AND meta_value = %s AND post_id = %s",$meta_key,$booking_date,$booking->post_id) );	

		
	foreach($booking_dates as $date){

	$meta_key2 = 'booking_time';
	$booking_times = $wpdb->get_results( $wpdb->prepare( 
	    "SELECT * FROM $wpdb->postmeta WHERE meta_key = %s AND meta_value = %s AND post_id = %s",$meta_key2,$booking_time,$date->post_id) );

	
	$total = 0;
		foreach($booking_times as $time){

			$meta_key3 = 'no_of_persons';
			$no_of_persons = $wpdb->get_results( $wpdb->prepare( 
			    "SELECT * FROM $wpdb->postmeta WHERE meta_key = %s AND post_id = %s",$meta_key3,$time->post_id) );
			

			
			$post_status = get_post_status ( $time->post_id ); 

			$booking_status = get_post_meta( $time->post_id,'booking_status',true );

			if($post_status == 'publish' && $booking_status == 'confirmed'){

				
				$booking_time_pers[$time->meta_value] = (int) $booking_time_pers[$time->meta_value] + (int) $no_of_persons[0]->meta_value;	
				


			}			

		} 	

	}

    }

		
	$unavailable_time_slot = false;
	
	if(!empty($booking_time_pers)){

		foreach($booking_time_pers as $time => $prev_per){
			
			$now_persons = $prev_per + $persons;

			if($now_persons > (int) $pstb_total_person){
				$unavailable_time_slot = true;
			}

		}
    }

	return $unavailable_time_slot;
	

}


function pstb_check_table_availability_timeslot_day_time_range($booking_date,$booking_time,$persons,$table_id,$time_until){

	global $wpdb;	
	//$pstb_total_person = get_option('pstb_total_person');
	$pstb_total_person = get_post_meta($table_id,'table_persons',true);

	$booking_time_pers = array();

	$meta_key = 'booking_table';
	$bookings = $wpdb->get_results( $wpdb->prepare( 
	    "SELECT * FROM $wpdb->postmeta WHERE meta_key = %s AND meta_value = %s",$meta_key,$table_id) );	

	foreach($bookings as $booking){

	$meta_key = 'booking_date';
	$booking_dates = $wpdb->get_results( $wpdb->prepare( 
	    "SELECT * FROM $wpdb->postmeta WHERE meta_key = %s AND meta_value = %s AND post_id = %s",$meta_key,$booking_date,$booking->post_id) );	
	}


   $booking_date_old = get_post_meta($booking_dates[0]->post_id,'booking_date',true);

   $booking_start_time_old = get_post_meta($booking_dates[0]->post_id,'booking_time',true);

   $booking_until_time_old = get_post_meta($booking_dates[0]->post_id,'booking_time_until',true);

   $booking_persons_old = get_post_meta($booking_dates[0]->post_id,'no_of_persons',true);	

   

  
   $booking_start_time_old = strtotime($booking_date_old.' '.str_replace('-',':',$booking_start_time_old));
   $booking_until_time_old = strtotime($booking_date_old.' '.str_replace('-',':',$booking_until_time_old));
   
	
   
   $booking_time = strtotime($booking_date.' '.str_replace('-',':',$booking_time));
   $time_until = strtotime($booking_date.' '.str_replace('-',':',$time_until));  
   

   $unavailable_time_range = false;

   if($booking_time >= $booking_start_time_old && $booking_time <= $booking_until_time_old){
   		$now_person = (int) $booking_persons_old + (int) $persons;
   		if((int)$now_person > (int)$pstb_total_person){
   			$unavailable_time_range = true;
   		}
   		
   }

   if($time_until >= $booking_start_time_old && $time_until <= $booking_until_time_old){
   		$now_person = (int) $booking_persons_old + (int) $persons;
   		if((int)$now_person > (int)$pstb_total_person){
   			$unavailable_time_range = true;
   		}
   }

	return $unavailable_time_range;
	

}

/* autofill repeated billing fields same as booking fields */
function pstb_set_checkout_bill_fields( $fields ) {   
			
	if(isset($_COOKIE['tb_step2_data'])) {
		$step2_data = array();
		parse_str($_COOKIE['tb_step2_data'], $step2_data);	
		$billing_first_name = $step2_data['billing_first_name'];
		$billing_last_name = $step2_data['billing_last_name'];
		$billing_phone = $step2_data['billing_phone'];
		$billing_email = $step2_data['billing_email'];
		$customer_note = $step2_data['customer_note'];			
	}


	 if(is_user_logged_in()){
	     $current_user = wp_get_current_user();
	     if($current_user->billing_first_name == ''){
	     	$fields['billing']['billing_first_name']['default'] = $billing_first_name;
	     }
	     if($current_user->billing_last_name == ''){
	     	$fields['billing']['billing_last_name']['default'] = $billing_last_name;
	     }
	     if($current_user->billing_phone == ''){
	     	$fields['billing']['billing_phone']['default'] = $billing_phone;
	     }
	     if($current_user->billing_email == ''){
	     	$fields['billing']['billing_email']['default'] = $billing_email;
	     }

	     $fields['order']['order_comments']['default'] = $customer_note;
	     

     }
     else{

     	$fields['billing']['billing_first_name']['default'] = $billing_first_name;
     	$fields['billing']['billing_last_name']['default'] = $billing_last_name;
     	$fields['billing']['billing_phone']['default'] = $billing_phone;
     	$fields['billing']['billing_email']['default'] = $billing_email;
     	$fields['order']['order_comments']['default'] = $customer_note;


     }

    return $fields;
}

add_filter( 'woocommerce_checkout_fields' , 'pstb_set_checkout_bill_fields' );


add_filter( 'body_class', function( $classes ) {
	return array_merge( $classes, array( get_option('pstb_functionality') ) );
} );