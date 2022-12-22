<?php 
/**
 * Register a meta box table booking post.
 */
class Table_Metabox {
 
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
            'pstb-table-meta-box',
            esc_html__( 'Table Fields', 'quick-table-booking' ),
            array( $this, 'pstb_render_metabox' ),
            'object',
            'advanced',
            'default'
        );
 
    }
 
    /**
     * Renders the meta box.
     */
    public function pstb_render_metabox( $post ) {
        // Add nonce for security and authentication.
        $pstb_total_person = get_option('pstb_total_person');


        wp_nonce_field( 'pstb_table_nonce_action', 'pstb_table_nonce' );
        $html = '<div class="pstb-settings pstb-metabox">';
        $html .= '<table>';
        
        $html .= '<tr valign="top">';
        $html .= '<th scope="row">';       
        $html .= '<label for="table_persons">'. esc_html__('Persons', 'quick-table-booking') .'</label>';
        $html .= '</th>';
        $table_persons = get_post_meta( $post->ID, 'table_persons', true );
        $html .= '<td>';       
        $html .= '<input type="number" name="table_persons" id="table_persons" class="pstb-back-field" value="'. esc_attr($table_persons) .'" min="0" max="'.$pstb_total_person.'" required=required />';
        $html .= '</td>';
        $html .= '</tr>';

        $html .= '<tr valign="top">';
        $html .= '<th scope="row">';       
        $html .= '<label for="table_persons_min">'. esc_html__('Minimum Persons', 'quick-table-booking') .'</label>';
        $html .= '</th>';
        $table_person_min = get_post_meta( $post->ID, 'table_persons_min', true );
        $html .= '<td>';       
        $html .= '<input type="number" name="table_persons_min" id="table_persons_min" class="pstb-back-field" value="'. esc_attr($table_person_min) .'" min="0" max="'.$pstb_total_person.'" />';
        $html .= '</td>';
        $html .= '</tr>'; 
       

        if(get_option('pstb_functionality')=='pstb_table_booking_opt'){

        $html .= '<tr valign="top">';
        $html .= '<th scope="row">';
        $html .= '<label for="table_shape">'. esc_html__('Shape', 'quick-table-booking') .'</label>';
        $html .= '</th>';
        $table_shape = get_post_meta( $post->ID, 'table_shape', true );
        $html .= '<td>';   
        $html .= '<select name="table_shape" id="table_shape" class="pstb-back-field" >';      
       
       
        if($table_shape=='rectangle'){
            
            $html .= '<option value="rectangle" selected="selected">'.esc_html__('Rectangle', 'quick-table-booking').'</option>';
        }
        else{
            $html .= '<option value="rectangle" >'.esc_html__('Rectangle', 'quick-table-booking').'</option>';
        }

        if($table_shape=="square"){
            $html .= '<option value="square" selected="selected">'.esc_html__('Square', 'quick-table-booking').'</option>';
        }
        else{
            $html .= '<option value="square" >'.esc_html__('Square', 'quick-table-booking').'</option>';
        }

        if($table_shape=="circle"){
            $html .= '<option value="circle" selected="selected">'.esc_html__('Circle', 'quick-table-booking').'</option>';
        }
        else{
            $html .= '<option value="circle" >'.esc_html__('Circle', 'quick-table-booking').'</option>';
        }
        
        $html .= '</select>';
        $html .= '<td>';
        $html .= '</tr>'; 

        }


        $html .= '<tr valign="top">';
        $html .= '<th scope="row">';
        $html .= '<label for="table_section">'. esc_html__('Section', 'quick-table-booking') .'</label>';
        $html .= '</th>';
        $table_section = get_post_meta( $post->ID, 'table_section', true );
        $html .= '<td>';   
        $html .= '<select name="table_section" id="table_section" class="pstb-back-field" >';   

        if($table_section=='A'){
            
            $html .= '<option value="A" selected="selected">'.esc_html__('A', 'quick-table-booking').'</option>';
        }
        else{
            $html .= '<option value="A" >'.esc_html__('A', 'quick-table-booking').'</option>';
        }

        if($table_section=="B"){
            $html .= '<option value="B" selected="selected">'.esc_html__('B', 'quick-table-booking').'</option>';
        }
        else{
            $html .= '<option value="B" >'.esc_html__('B', 'quick-table-booking').'</option>';
        }

        if($table_section=="C"){
            $html .= '<option value="C" selected="selected">'.esc_html__('C', 'quick-table-booking').'</option>';
        }
        else{
            $html .= '<option value="C" >'.esc_html__('C', 'quick-table-booking').'</option>';
        }
        
        $html .= '</select>';
        $html .= '<td>';
        $html .= '</tr>'; 


        $html .= '<tr valign="top">';
        $html .= '<th scope="row">';       
        $html .= '<label for="table_sequence_no">'. esc_html__('Sequence No.', 'quick-table-booking') .'</label>';
        $html .= '</th>';
        $table_sequence_no = get_post_meta( $post->ID, 'table_sequence_no', true );
        $html .= '<td>';       
        $html .= '<input type="number" name="table_sequence_no" id="table_sequence_no" class="pstb-back-field" value="'. esc_attr($table_sequence_no) .'" min="1" />';
        $html .= '</td>';
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
        $nonce_name   = isset( $_POST['pstb_table_nonce'] ) ? sanitize_text_field($_POST['pstb_table_nonce'] ) : '';
        $nonce_action = 'pstb_table_nonce_action';
 

        $table_persons   = isset( $_POST['table_persons'] ) ? sanitize_text_field( $_POST['table_persons'] ) : '';

        $table_persons_min   = isset( $_POST['table_persons_min'] ) ? sanitize_text_field( $_POST['table_persons_min'] ) : '';

        $table_shape   = isset( $_POST['table_shape'] ) ? sanitize_text_field( $_POST['table_shape'] ) : '';

        $table_section   = isset( $_POST['table_section'] ) ? sanitize_text_field( $_POST['table_section'] ) : '';

        $table_sequence_no   = isset( $_POST['table_sequence_no'] ) ? sanitize_text_field( $_POST['table_sequence_no'] ) : '';

        $table_status   = isset( $_POST['table_status'] ) ? sanitize_text_field( $_POST['table_status'] ) : '';      


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

        update_post_meta( $post_id, 'table_persons', $table_persons );
        update_post_meta( $post_id, 'table_persons_min', $table_persons_min );
        update_post_meta( $post_id, 'table_shape', $table_shape );
        update_post_meta( $post_id, 'table_section', $table_section );        
        update_post_meta( $post_id, 'table_sequence_no', $table_sequence_no );
        
        remove_action('save_post', array( $this, 'save_metabox' ));   

        if (is_wp_error($post_id)) {
            $errors = $post_id->get_error_messages();
            foreach ($errors as $error) {
                echo $error;
            }
        } 
        
        add_action('save_post', array( $this, 'save_metabox' ), 10, 2 );

    }
}
 
new Table_Metabox();