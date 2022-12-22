<?php 

function pstb_disable_dates(){

	$pstb_holidays = get_option('pstb_holidays'); 
	$pstb_week_off = get_option('pstb_week_off'); 
	$pstb_total_person = get_option('pstb_total_person');


	$pstb_week_off = implode(',', $pstb_week_off);
	
	$data = array();
	$data['pstb_holidays'] = $pstb_holidays;
	$data['pstb_week_off'] = $pstb_week_off;

	echo json_encode($data);	
	die;
}
add_action('wp_ajax_pstb_disable_dates', 'pstb_disable_dates');
add_action('wp_ajax_nopriv_pstb_disable_dates', 'pstb_disable_dates');


function pstb_disable_time_slots(){

	$booking_date = sanitize_text_field($_POST['booking_date']);

    if($booking_date !=''){    

		$unavailable_time_slots = pstb_get_unavailable_time_slots_of_day($booking_date);	

    }

	echo json_encode($unavailable_time_slots);	
	die;
}
add_action('wp_ajax_pstb_disable_time_slots', 'pstb_disable_time_slots');
add_action('wp_ajax_nopriv_pstb_disable_time_slots', 'pstb_disable_time_slots');


function pstb_add_booking_data(){
	
	$step1_data = pstb_sanitize_text_or_array_field( $_POST['step1_data'] );
	$step2_data = pstb_sanitize_text_or_array_field( $_POST['step2_data'] );
	$table_data = pstb_sanitize_text_or_array_field( $_POST['table_data'] );

	$booking_data = array();
	
	$hide_till_time = get_option('pstb_hide_till_time');

	foreach($step1_data as $data1){

		$booking_data[$data1['name']] = $data1['value'];

	}

	foreach($step2_data as $data2){

		$booking_data[$data2['name']] = $data2['value'];

	}

	foreach($table_data as $data3){

		$booking_data[$data3['name']] = $data3['value'];

	}

	$booking_date = $booking_data['booking_date'];
	$booking_time = $booking_data['booking_time'];
	
	if($hide_till_time!="yes"){
		$booking_time_until = $booking_data['booking_time_until'];
	}
	$first_name = $booking_data['billing_first_name'];
	$last_name = $booking_data['billing_last_name'];
	$email = $booking_data['billing_email'];
	$phone = $booking_data['billing_phone'];
	$customer_note = $booking_data['customer_note'];
	$no_of_persons = $booking_data['booking_persons'];
	$sel_table = $booking_data['sel_table'];
	
	if($hide_till_time=="yes"){
		$booking_title = 'Booking '.$booking_date.' '.$booking_time;
	}
	else{
		$booking_title = 'Booking '.$booking_date.' '.$booking_time.' : '.$booking_time_until;
	}

	$my_post = array(
	'post_title'    => $booking_title,
	'post_content'  => '',
	'post_status'   => 'publish',
	'post_author'   => 1,
	'post_type'     => 'object-booking'		
	);
	
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

		update_post_meta( $bid, 'first_name', $first_name );
		update_post_meta( $bid, 'last_name', $last_name );
		update_post_meta( $bid, 'email', $email );
		update_post_meta( $bid, 'phone', $phone );
		update_post_meta( $bid, 'customer_note', $customer_note );
		update_post_meta( $bid, 'booking_status', 'confirmed' );
		update_post_meta( $bid, 'no_of_persons', $no_of_persons );
		update_post_meta( $bid, 'booking_code', $booking_code );	
		update_post_meta( $bid, 'booking_table', $sel_table );	
		pstb_auto_mail_responder(1,$bid);
		pstb_auto_mail_responder(2,$bid);

		$booking_table_title = get_the_title($sel_table);
		$booking_table_section = get_post_meta($sel_table,'table_section',true);

		if($hide_till_time=="yes"){			
			$booking_time_until = '';
		}	


		$ans = array( 
					  "ans"=>1,
					  "bcode"=>$booking_code,
			          "bdate"=>$booking_date,
			          "btime"=>$booking_time,
			          "btimeuntil"=>$booking_time_until,
			          "bpersons"=>$no_of_persons,
			          "bfirst_name"=>$first_name,
					  "blast_name"=>$last_name,
			          "bemail"=>$email,
			          "bphone"=>$phone,
			          "bcustomer_note"=>$customer_note,
			          "btable_title"=>$booking_table_title,
			          "btable_section"=>$booking_table_section,		  	          
			    );

	}
	else{
		$ans = array("ans"=>0);
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

	echo json_encode($ans);
	die;

}
add_action('wp_ajax_pstb_add_booking_data', 'pstb_add_booking_data');
add_action('wp_ajax_nopriv_pstb_add_booking_data', 'pstb_add_booking_data');

function pstb_check_person_availability_timeslot(){

	$persons = sanitize_text_field( $_POST['persons'] );
	$time = sanitize_text_field( $_POST['time'] );
	$date = sanitize_text_field( $_POST['date'] );	
	
	$ans = pstb_check_person_availability_timeslot_day($date,$time,$persons);
	
	if($ans){
		$ans = array( "ans"=>1 );
	}
	else{
		$ans = array( "ans"=>0 );
	}
	echo json_encode($ans);
	die;

}
add_action('wp_ajax_pstb_check_person_availability_timeslot', 'pstb_check_person_availability_timeslot');
add_action('wp_ajax_nopriv_pstb_check_person_availability_timeslot', 'pstb_check_person_availability_timeslot');

function pstb_disp_tables(){

	$persons = sanitize_text_field( $_POST['persons'] );
	$time = sanitize_text_field( $_POST['time'] );
	$date = sanitize_text_field( $_POST['date'] );	
	if(isset($_POST['time_until'])){
		$time_until = sanitize_text_field( $_POST['time_until'] );
	}
		
	$html .= '<h3 class="tbbs-title">'.esc_html__( 'Select Object', 'quick-table-booking' ).'</h3>'; 
     
	$args = array(
	'numberposts' => -1,
	'post_type'   => 'object',
	'order' => 'ASC'
	);

	$tables = get_posts( $args );

	$A_sec_tables = array();
	$B_sec_tables = array();
	$C_sec_tables = array();

	if ( $tables ) {
        foreach ( $tables as $post ) : 
             
             $section = get_post_meta($post->ID, 'table_section', true );
             $table_sequence_no = (int) get_post_meta($post->ID, 'table_sequence_no', true );
             if($section == 'A'){
             	$A_sec_tables[$table_sequence_no] = $post->ID;
             }
             if($section == 'B'){
             	$B_sec_tables[$table_sequence_no] = $post->ID;
             }	
             if($section == 'C'){
             	$C_sec_tables[$table_sequence_no] = $post->ID;
             }

        endforeach;        
    }
     

     ksort($A_sec_tables);
     ksort($B_sec_tables);
     ksort($C_sec_tables);

     $html .= '<div id="sec-A" class="section">'; 
     $html .= '<h3 class="section-name">'.esc_html__( 'Section A', 'quick-table-booking' ).'</h3>'; 

     

     foreach($A_sec_tables as $tid){

     	$disable = '';
     	$unavailable = false;
     	$table_status = 'available';
     	$table_persons_max = get_post_meta($tid, 'table_persons', true );
     	$table_persons_min = get_post_meta($tid, 'table_persons_min', true );
     	$table_shape = '';
     	if(get_option('pstb_functionality')=='pstb_table_booking_opt'){

     		$table_shape = get_post_meta( $tid, 'table_shape', true );

     	}

     	$title = get_the_title($tid);
     	$unavailable = pstb_check_table_availability_timeslot_day($date,$time,$persons,$tid);
     	if($time_until != ''){
     		$unavailable_time_range = pstb_check_table_availability_timeslot_day_time_range($date,$time,$persons,$tid,$time_until);
     	}

     	if((int) $persons > (int) $table_persons_max ){
     		$disable = 'disabled=disabled';     		
     	}    	
     	else if((int) $persons < (int) $table_persons_min ){
     		$disable = 'disabled=disabled';     		
     	}
     	else if($unavailable){
     		$disable = 'disabled=disabled';     		
     	}
     	else if($unavailable_time_range){
     		$disable = 'disabled=disabled';  
     	}


     	$html .= '<div id="d_'.esc_attr($tid).'" class="table '.esc_attr($table_shape).'">'; 
     	$html .= '<h4 class="table-name">'.$title.'</h4>';
     	$html .= '<p class="table-person">'.esc_html__( 'Max. Persons', 'quick-table-booking' ).': '.$table_persons_max.'</p>';
      	$html .= '<p class="table-person">'.esc_html__( 'Min. Persons', 'quick-table-booking' ).': '.$table_persons_min.'</p>';
      	$html .= '<input type="radio" name="sel_table" value="'.esc_attr($tid).'" id="inp_'.esc_attr($tid).'" '.esc_attr($disable).' />';      	
      	$html .= '<label for="inp_'.esc_attr($tid).'">'.esc_html__( 'Select', 'quick-table-booking' ).'</label>';      	
     	$html .= '</div>'; 
     }
     $html .= '</div>';
     $html .= '<div id="sec-B" class="section">';
     $html .= '<h3 class="section-name">'.esc_html__( 'Section B', 'quick-table-booking' ).'</h3>';   
     foreach($B_sec_tables as $tid){
     	$disable = '';
     	$unavailable = false;
     	$table_persons_max = get_post_meta($tid, 'table_persons', true );
     	$table_persons_min = get_post_meta($tid, 'table_persons_min', true );
     	$table_shape = '';
     	if(get_option('pstb_functionality')=='pstb_table_booking_opt'){

     		$table_shape = get_post_meta( $tid, 'table_shape', true );

     	}
     	$title = get_the_title($tid);
     	$unavailable = pstb_check_table_availability_timeslot_day($date,$time,$persons,$tid);

     	if($time_until != ''){
     		$unavailable_time_range = pstb_check_table_availability_timeslot_day_time_range($date,$time,$persons,$tid,$time_until);
     	}



     	if((int) $persons > (int) $table_persons_max ){
     		$disable = 'disabled=disabled';
     	}    	
     	else if((int) $persons < (int) $table_persons_min ){
     		$disable = 'disabled=disabled';
     	}
     	else if($unavailable){
     		$disable = 'disabled=disabled';     		
     	}
     	else if($unavailable_time_range){
     		$disable = 'disabled=disabled';  
     	}


     	$html .= '<div id="d_'.esc_attr($tid).'" class="table '.esc_attr($table_shape).'">'; 
     	$html .= '<h4 class="table-name">'.$title.'</h4>';
     	$html .= '<p class="table-person">'.esc_html__( 'Max. Persons', 'quick-table-booking' ).': '.$table_persons_max.'</p>';
      	$html .= '<p class="table-person">'.esc_html__( 'Min. Persons', 'quick-table-booking' ).': '.$table_persons_min.'</p>';
      	$html .= '<input type="radio" name="sel_table" value="'.esc_attr($tid).'" id="inp_'.esc_attr($tid).'" '.esc_attr($disable).' />';
      	$html .= '<label for="inp_'.esc_attr($tid).'">'.esc_html__( 'Select', 'quick-table-booking' ).'</label>';
     	$html .= '</div>'; 
     }

     $html .= '</div>';
     $html .= '<div id="sec-C" class="section">';
     $html .= '<h3 class="section-name">'.esc_html__( 'Section C','quick-table-booking' ).' </h3>';   
     foreach($C_sec_tables as $tid){
     	$disable = '';
     	$unavailable = false;
     	$table_personsersons_max = get_post_meta($tid, 'table_persons', true );
     	$table_persons_min = get_post_meta($tid, 'table_persons_min', true );
     	$table_shape = '';
     	if(get_option('pstb_functionality')=='pstb_table_booking_opt'){

     		$table_shape = get_post_meta( $tid, 'table_shape', true );

     	}
     	$title = get_the_title($tid);
     	$unavailable = pstb_check_table_availability_timeslot_day($date,$time,$persons,$tid);
     	if($time_until != ''){
     		$unavailable_time_range = pstb_check_table_availability_timeslot_day_time_range($date,$time,$persons,$tid,$time_until);
     	}


     	if((int) $persons > (int) $table_persons_max ){
     		$disable = 'disabled=disabled';
     	}    	
     	else if((int) $persons < (int) $table_persons_min ){
     		$disable = 'disabled=disabled';
     	}
     	else if($unavailable){
     		$disable = 'disabled=disabled';     		
     	}
     	else if($unavailable_time_range){
     		$disable = 'disabled=disabled';  
     	}

     	$html .= '<div id="d_'.esc_attr($tid).'" class="table '.esc_attr($table_shape).'">'; 
     	$html .= '<h4 class="table-name">'.$title.'</h4>';
     	$html .= '<p class="table-person">'.esc_html__( 'Max. Persons', 'quick-table-booking' ).': '.$table_persons_max.'</p>';
      	$html .= '<p class="table-person">'.esc_html__( 'Min. Persons', 'quick-table-booking' ).': '.$table_persons_min.'</p>';
      	$html .= '<input type="radio" name="sel_table" value="'.esc_attr($tid).'" id="inp_'.esc_attr($tid).'" '.esc_attr($disable).' />';
      	$html .= '<label for="inp_'.esc_attr($tid).'">'.esc_html__( 'Select', 'quick-table-booking' ).'</label>';
     	$html .= '</div>'; 
     }

     $html .= '</div>';

     $html .= '<div class="field-wrp step-btn-sec">';
     $html .= '<input type="button" value="'.esc_html__( 'Prev', 'quick-table-booking' ).'" class="psbk-btn psbk-prev-btn psbk-goto-first" />';

      $disableNext = '';	
      if(empty($tables)){
      	$disableNext = ' disabled=disabled ';
      }

	 $html .= '<input type="button" value="'.esc_html__( 'Next', 'quick-table-booking' ).'" class="psbk-btn psbk-next-btn pstb-table-step" '.$disableNext.'/>';
	 $html .= '</div>';  
	 echo $html;

	die;

}
add_action('wp_ajax_pstb_disp_tables', 'pstb_disp_tables');
add_action('wp_ajax_nopriv_pstb_disp_tables', 'pstb_disp_tables');