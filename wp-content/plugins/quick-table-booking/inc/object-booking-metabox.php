<?php 
/**
 * Register a meta box table booking post.
 */
class Table_Booking_Metabox {
 
    /**
     * Constructor.
     */
    public function __construct() {
        if ( is_admin() ) {
            add_action( 'load-post.php',     array( $this, 'pstb_init_metabox' ) );
            add_action( 'load-post-new.php', array( $this, 'pstb_init_metabox' ) );
        }
 
    }
 
    /**
     * Meta box initialization.
     */
    public function pstb_init_metabox() {
        add_action( 'add_meta_boxes', array( $this, 'pstb_add_metabox'  )        );
        add_action( 'save_post',      array( $this, 'pstb_save_metabox' ), 10, 2 );
    }
 
    /**
     * Adds the meta box.
     */
    public function pstb_add_metabox() {
        add_meta_box(
            'pstb-booking-meta-box',
            esc_html__( 'Booking Fields', 'quick-table-booking' ),
            array( $this, 'pstb_render_metabox' ),
            'object-booking',
            'advanced',
            'default'
        );
 
    }
 
    /**
     * Renders the meta box.
     */
    public function pstb_render_metabox( $post ) {
        // Add nonce for security and authentication.
        wp_nonce_field( 'pstb_booking_nonce_action', 'pstb_booking_nonce' );
        $pstb_start_time = get_option('pstb_start_time');
        $pstb_end_time = get_option('pstb_end_time');
        $hide_till_time = get_option('pstb_hide_till_time');

        if($pstb_start_time == ''){
            $pstb_start_time = "10-00 AM";
        }

        if($pstb_end_time == ''){
            $pstb_end_time = "10-00 PM";
        }        

        $html = '<div class="pstb-settings pstb-metabox">';
        $html .= '<table>';
        $html .= '<tr valign="top">';
        $html .= '<th scope="row">';       
        $html .= '<label for="booking_code">'. esc_html__('Booking Code', 'quick-table-booking') .'</label>';
        $html .= '</th>';
        $booking_code = get_post_meta( $post->ID, 'booking_code', true );
        $html .= '<td>';       
        $html .= '<input type="text" name="booking_code" id="booking_code" class="pstb-back-field" value="'. esc_attr($booking_code) .'" />';
        $html .= '</td>';
        $html .= '</tr>';

        $html .= '<tr valign="top">';
        $html .= '<th scope="row">';  
        $html .= '<label for="booking_date">'. esc_html__('Booking Date', 'quick-table-booking') .'</label>';
        $html .= '</th>';
        $html .= '<td>'; 
        $booking_date = get_post_meta( $post->ID, 'booking_date', true );
        $html .= '<input type="text" name="booking_date" id="booking_date" class="pstb-back-field" value="'. esc_attr($booking_date) .'" readonly="readonly" />';
        $html .= '</td>';  
        $html .= '</tr>';  

 
        $html .= '<tr valign="top">';
        $html .= '<th scope="row">';          
        $html .= '<label for="booking_time">'. esc_html__('Booking From When', 'quick-table-booking') .'</label>';
        $booking_time = get_post_meta( $post->ID, 'booking_time', true );
        $html .= '</th>';        
        $html .= '<td>'; 
        $html .= '<select name="booking_time" required="required" class="pstb-back-field">';
        $html .= '<option value="">select</option>';

        $a = DateTime::createFromFormat("g-i A", $pstb_start_time);
        $a = $a->getTimestamp();

        $b = DateTime::createFromFormat("g-i A", $pstb_end_time);
        $b = $b->getTimestamp();

        $t=$a;
        while($t<=$b){
            $time = date("g-i A",$t);           
            $selected = '';
            if($booking_time==$time){
                $selected = 'selected=selected';
            }

            $html .= '<option value="'.esc_attr($time).'" '.$selected.'>'.$time.'</option>';    
            $t = strtotime('+30 minutes',$t);
        }


        $html .= '</select >';
        $html .= '</td>'; 
        $html .= '</tr >'; 


        if($hide_till_time!="yes"){
            $html .= '<tr valign="top">';
            $html .= '<th scope="row">';   
            $html .= '<label for="booking_time_until">'. esc_html__('Booking until?', 'quick-table-booking') .'</label>';
            $booking_time_until = get_post_meta( $post->ID, 'booking_time_until', true );
            $html .= '</th>';
            $html .= '<td>';
            $html .= '<select name="booking_time_until" required="required" class="pstb-back-field">';
            $html .= '<option value="">'.esc_html__('select', 'quick-table-booking').'</option>';

            $a = DateTime::createFromFormat("g-i A", $pstb_start_time);
            $a = $a->getTimestamp();

            $b = DateTime::createFromFormat("g-i A", $pstb_end_time);
            $b = $b->getTimestamp();

            $t=$a;
            while($t<=$b){
                $time = date("g-i A",$t);            
                $selected = '';   
                if($booking_time_until==$time){
                    $selected = 'selected=selected';
                }
                $html .= '<option value="'.$time.'" '.$selected.'>'.$time.'</option>';  
                $t = strtotime('+30 minutes',$t);
            }

            $html .= '</select >';
            $html .= '</td>';
            $html .= '</tr >'; 
        }


        if(get_option('pstb_functionality')=='pstb_table_booking_opt' || get_option('pstb_functionality') == 'pstb_hotel_room_booking_opt' ){


                $html .= '<tr valign="top">';
                $html .= '<th scope="row">';
                $html .= '<label for="no_of_persons">'. esc_html__('No of Persons', 'quick-table-booking') .'</label>';
                $html .= '</th>';

                $no_of_persons = get_post_meta( $post->ID, 'no_of_persons', true );

                $html .= '<td>';
                $html .= '<input type="number" name="no_of_persons" id="no_of_persons" class="pstb-back-field" value="'. esc_attr($no_of_persons) .'" />';
                $html .= '</td>';
                $html .= '</tr>';

                 $html .= '<tr valign="top">';
                $html .= '<th scope="row">';
                $html .= '<label for="booking_table">'. esc_html__('Booking Object', 'quick-table-booking') .'</label>';
                $html .= '</th>';

                $booking_table = get_post_meta( $post->ID, 'booking_table', true );

                $args = array(
                'numberposts' => -1,
                'post_type'   => 'object',
                'order' => 'ASC'
                );

                $tables = get_posts( $args );      


                $html .= '<td>';
                $html .= '<select name="sel_table">';        
                $html .= '<option value="0">'.esc_html__('select', 'quick-table-booking').'</option>';
                if ( $tables ) {
                foreach ( $tables as $table ) :
                
                $selected = '';
                if((int)$table->ID==(int)$booking_table){
                    $selected = 'selected=selected';
                }  


                $html .= '<option value="'.esc_attr($table->ID).'" '.$selected.'>'.$table->post_title.'</option>';
                endforeach;
                }
                $html .= '</select>';     
                $html .= '</td>';        
                $html .= '</tr>';

        }


        $html .= '<tr valign="top">';
        $html .= '<th scope="row">';
        $html .= '<label for="first_name">'. esc_html__('First Name', 'quick-table-booking') .'</label>';
        $html .= '</th>';

        $first_name = get_post_meta( $post->ID, 'first_name', true );

        $html .= '<td>';
        $html .= '<input type="text" name="first_name" id="first_name" class="pstb-back-field" value="'. esc_attr($first_name) .'" />';
        $html .= '</td>';
        $html .= '</tr>';

        $html .= '<tr valign="top">';
        $html .= '<th scope="row">';        
        $html .= '<label for="last_name">'. esc_html__('Last Name', 'quick-table-booking') .'</label>';
        $html .= '</th>';        
        $last_name = get_post_meta( $post->ID, 'last_name', true );
        $html .= '<td>';
        $html .= '<input type="text" name="last_name" id="last_name" class="pstb-back-field" value="'. esc_attr($last_name) .'" />';
        $html .= '</td>';
        $html .= '</tr>';

        $html .= '<tr valign="top">';
        $html .= '<th scope="row">';       
        $html .= '<label for="email">'. esc_html__('Email', 'quick-table-booking') .'</label>';
        $html .= '</th>';

        $email = get_post_meta( $post->ID, 'email', true );

        $html .= '<td>';
        $html .= '<input type="text" name="email" id="email" class="pstb-back-field" value="'. esc_attr($email) .'" />';
        $html .= '</td>';
        $html .= '</tr>';

        $html .= '<tr valign="top">';
        $html .= '<th scope="row">';      
        $html .= '<label for="phone">'. esc_html__('Phone', 'quick-table-booking') .'</label>';
        $html .= '</th>';

        $phone = get_post_meta( $post->ID, 'phone', true );

        $html .= '<td>';      
        $html .= '<input type="text" name="phone" id="phone" class="pstb-back-field" value="'. esc_attr($phone) .'" />';
        $html .= '</td>';
        $html .= '</tr>';

       
        $html .= '<tr valign="top">';
        $html .= '<th scope="row">';
        $html .= '<label for="customer_note">'. esc_html__('Message', 'quick-table-booking') .'</label>';
        $html .= '</th>';
         $html .= '<td>';      
        $customer_note = get_post_meta( $post->ID, 'customer_note', true );
        $html .= '<textarea name="customer_note" id="customer_note" class="pstb-back-field" >'.esc_textarea($customer_note).'</textarea>';
        $html .= '</td>';
        $html .= '</tr>';
       

        $html .= '<tr valign="top">';
        $html .= '<th scope="row">';
        $html .= '<label for="booking_status">'. esc_html__('Status', 'quick-table-booking') .'</label>';
        $html .= '</th>';
        $booking_status = get_post_meta( $post->ID, 'booking_status', true );
        $html .= '<td>';   
        $html .= '<select name="booking_status" id="booking_status" class="pstb-back-field" >';      
       
       
        if($booking_status=='cancelled'){
            
            $html .= '<option value="cancelled" selected="selected">'.esc_html__('cancelled', 'quick-table-booking').'</option>';
        }
        else{
            $html .= '<option value="cancelled" >'.esc_html__('cancelled', 'quick-table-booking').'</option>';
        }

        if($booking_status=="confirmed"){
            $html .= '<option value="confirmed" selected="selected">'.esc_html__('confirmed', 'quick-table-booking').'</option>';
        }
        else{
            $html .= '<option value="confirmed" >'.esc_html__('confirmed', 'quick-table-booking').'</option>';
        }
        
        $html .= '</select>';
        $html .= '<td>';
        $html .= '</tr>'; 
        $html .='</table>';
        $html .= '</div>';
        echo $html;
    }
 
    /**
     * Handles saving the meta box.
     *
     * @param int     $post_id Post ID.
     * @param WP_Post $post    Post object.
     * @return null
     */
    public function pstb_save_metabox( $post_id, $post ) {
        // Add nonce for security and authentication.
        $nonce_name   = isset( $_POST['pstb_booking_nonce'] ) ? sanitize_text_field($_POST['pstb_booking_nonce'] ) : '';
        $nonce_action = 'pstb_booking_nonce_action';
 
        $hide_till_time = get_option('pstb_hide_till_time');

        $booking_date   = isset( $_POST['booking_date'] ) ? sanitize_text_field( $_POST['booking_date'] ) : '';
        $booking_time   = isset( $_POST['booking_time'] ) ? sanitize_text_field( $_POST['booking_time'] ) : '';

        if($hide_till_time!="yes"){

        $booking_time_until   = isset( $_POST['booking_time_until'] ) ? sanitize_text_field( $_POST['booking_time_until'] ) : '';

        }

        $no_of_persons   = isset( $_POST['no_of_persons'] ) ? sanitize_text_field( $_POST['no_of_persons'] ) : '';
        $first_name   = isset( $_POST['first_name'] ) ? sanitize_text_field( $_POST['first_name'] ) : '';
        $last_name   = isset( $_POST['last_name'] ) ? sanitize_text_field( $_POST['last_name'] ) : '';
        $email   = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';
        $phone   = isset( $_POST['phone'] ) ? sanitize_text_field( $_POST['phone'] ) : '';
        $customer_note   = isset( $_POST['customer_note'] ) ? sanitize_text_field( $_POST['customer_note'] ) : '';
        $booking_status   = isset( $_POST['booking_status'] ) ? sanitize_text_field( $_POST['booking_status'] ) : '';


        // Check if nonce is valid.
        if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) ) {
            return;
        }
 
        // Check if user has permissions to save data.
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
 
        // Check if not an autosave.
        if ( wp_is_post_autosave( $post_id ) ) {
            return;
        }
 
        // Check if not a revision.
        if ( wp_is_post_revision( $post_id ) ) {
            return;
        }

        update_post_meta( $post_id, 'booking_date', $booking_date );
        update_post_meta( $post_id, 'booking_time', $booking_time );        
        if($hide_till_time!="yes"){
            update_post_meta( $post_id, 'booking_time_until', $booking_time_until );
        }
        update_post_meta( $post_id, 'no_of_persons', $no_of_persons );        
        update_post_meta( $post_id, 'first_name', $first_name );
        update_post_meta( $post_id, 'last_name', $last_name );
        update_post_meta( $post_id, 'email', $email );        
        update_post_meta( $post_id, 'phone', $phone );
        update_post_meta( $post_id, 'customer_note', $customer_note );        
        update_post_meta( $post_id, 'booking_status', $booking_status );  

        if($hide_till_time=="yes"){
            $btitle = 'Booking '.$booking_date.' '.$booking_time;
        }
        else{
            $btitle = 'Booking '.$booking_date.' '.$booking_time.' : '.$booking_time_until; 
        }         

        $my_post = array(
        'ID'           => $post_id,
        'post_title'   => $btitle        
        );   


        remove_action('save_post', array( $this, 'save_metabox' ));        

        wp_update_post( $my_post );

        if (is_wp_error($post_id)) {
            $errors = $post_id->get_error_messages();
            foreach ($errors as $error) {
                echo $error;
            }
        } 
        
        add_action('save_post', array( $this, 'save_metabox' ), 10, 2 );

    }
}
 
new Table_Booking_Metabox();