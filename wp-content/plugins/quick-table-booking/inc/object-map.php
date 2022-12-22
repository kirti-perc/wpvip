<?php 



function pstb_register_table_map_page() {  

add_submenu_page('edit.php?post_type=object', 'Object Map','Object Map', 'edit_posts', basename(__FILE__),'pstb_table_map_page');

}
add_action('admin_menu', 'pstb_register_table_map_page');

function pstb_table_map_page()
{

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

     $html = '';

     $html .= '<div class="table-map-sec">';
     $html .= '<h2 class="pstb-back-title">'.esc_html__( 'Object Map', 'quick-table-booking' ).'</h2>';
     $html .= '<div id="sec-A" class="section">'; 
     $html .= '<h3 class="section-name">Section A</h3>';   
     foreach($A_sec_tables as $tid){

      $person = get_post_meta($tid, 'table_persons', true );
      $table_persons_min = get_post_meta($tid, 'table_persons_min', true );

      
      $table_shape = '';

      if(get_option('pstb_functionality')=='pstb_table_booking_opt'){

        $table_shape = get_post_meta( $tid, 'table_shape', true );

      }
      

      $title = get_the_title($tid);

      $html .= '<div id="'.$tid.'" class="table '.$table_shape.'">'; 
      $html .= '<h4 class="table-name">'.$title.'</h4>';
     $html .= '<p class="table-person">'.esc_html__( 'Max. Persons', 'quick-table-booking' ).': '.$person.'</p>';
      $html .= '<p class="table-person">'.esc_html__( 'Min. Persons', 'quick-table-booking' ).': '.$table_persons_min.'</p>';
      $html .= '</div>'; 
     }
     $html .= '</div>';
     $html .= '<div id="sec-B" class="section">';
     $html .= '<h3 class="section-name">Section B</h3>';   
     foreach($B_sec_tables as $tid){

      $person = get_post_meta($tid, 'table_persons', true );
      $table_persons_min = get_post_meta($tid, 'table_persons_min', true );
      $table_shape = '';
      if(get_option('pstb_functionality')=='pstb_table_booking_opt'){

        $table_shape = get_post_meta( $tid, 'table_shape', true );

      }
      
      $title = get_the_title($tid);

      $html .= '<div id="'.$tid.'" class="table '.$table_shape.'">'; 
      $html .= '<h4 class="table-name">'.$title.'</h4>';
     $html .= '<p class="table-person">'.esc_html__( 'Max. Persons', 'quick-table-booking' ).': '.$person.'</p>';
      $html .= '<p class="table-person">'.esc_html__( 'Min. Persons', 'quick-table-booking' ).': '.$table_persons_min.'</p>';
      $html .= '</div>'; 
     }

     $html .= '</div>';
     $html .= '<div id="sec-C" class="section">';
     $html .= '<h3 class="section-name">Section C</h3>';   
     foreach($C_sec_tables as $tid){

      $person = get_post_meta($tid, 'table_persons', true );
      $table_persons_min = get_post_meta($tid, 'table_persons_min', true );
      $table_shape = '';
      if(get_option('pstb_functionality')=='pstb_table_booking_opt'){

        $table_shape = get_post_meta( $tid, 'table_shape', true );

      }
      
      $title = get_the_title($tid);

      $html .= '<div id="'.$tid.'" class="table '.$table_shape.'">'; 
      $html .= '<h4 class="table-name">'.$title.'</h4>';
      $html .= '<p class="table-person">'.esc_html__( 'Max. Persons', 'quick-table-booking' ).': '.$person.'</p>';
      $html .= '<p class="table-person">'.esc_html__( 'Min. Persons', 'quick-table-booking' ).': '.$table_persons_min.'</p>';
       
      $html .= '</div>'; 
     }

     $html .= '</div>';
     $html .= '</div>';
     echo $html;
 
} 
 
