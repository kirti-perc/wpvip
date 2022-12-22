<?php 

function pstb_register_settings() {   

  $tp_args = array(
    'default' => 50,
  );

  $hd_args = array(
    'default' => "05-10-2021,21-10-2021",
  );

  $st_args = array(
    'default' => "10-00 AM",
  );

  $wo_args = array(
    'default' => array(4),
  );

  $et_args = array(
    'default' => "10-00 PM",
  );

  $HTT_args = array(
    'default' => "no",
  );

  $ps_func_args = array(
    'pstb_functionality' => "pstb_table_booking_opt",
  );

   register_setting( 'pstb_options_group', 'pstb_total_person', $tp_args);
   register_setting( 'pstb_options_group', 'pstb_holidays', $hd_args);
   register_setting( 'pstb_options_group', 'pstb_week_off', $wo_args); 
   register_setting( 'pstb_options_group', 'pstb_start_time', $st_args); 
   register_setting( 'pstb_options_group', 'pstb_end_time', $et_args);
   register_setting( 'pstb_options_group', 'pstb_hide_till_time', $HTT_args);
   register_setting( 'pstb_options_group', 'pstb_functionality', $ps_func_args);


}
add_action( 'admin_init', 'pstb_register_settings' );

function pstb_register_options_page_new() {  

add_submenu_page('edit.php?post_type=object-booking', 'TB Settings','TB Settings', 'edit_posts', basename(__FILE__),'pstb_options_page');

}
add_action('admin_menu', 'pstb_register_options_page_new');

function pstb_options_page()
{
?>
  <div class="pstb-settings"> 


  <h2 class="pstb-back-title"><?php esc_html_e( 'Booking', 'quick-table-booking' ); ?></h2>
  <form method="post" action="options.php">
  <?php settings_fields( 'pstb_options_group' ); ?>  
  <p><?php esc_html_e( 'Here all options to save settings in booking', 'quick-table-booking' ); ?></p>
  <table>
  <tr valign="top">
  <th scope="row">
    <label for="ps_functionality">
    <?php 
    esc_html_e( 'Which kind of layout you want to set?', 'quick-table-booking' ); 
    ?>      
    </label>
  </th>
  <td>
  <select name="pstb_functionality" id="pstb_functionality">

      <option value="pstb_table_booking_opt" <?php if(get_option('pstb_functionality')=='pstb_table_booking_opt'){ echo 'selected=selected'; } ?>><?php esc_html_e( 'Restaurant Table Booking', 'quick-table-booking' ); ?></option>
      <option value="pstb_hotel_room_booking_opt" <?php if(get_option('pstb_functionality')=='pstb_hotel_room_booking_opt'){ echo 'selected=selected'; } ?>><?php esc_html_e( 'Hotel Room Booking', 'quick-table-booking' ); ?></option>
      <option value="pstb_dr_appoint_booking_opt" <?php if(get_option('pstb_functionality')=='pstb_dr_appoint_booking_opt'){ echo 'selected=selected'; } ?>><?php esc_html_e( 'Dr’s Appointment Booking', 'quick-table-booking' ); ?></option>
      <option value="pstb_saloon_booking_opt" <?php if(get_option('pstb_functionality')=='pstb_saloon_booking_opt'){ echo 'selected=selected'; } ?>><?php esc_html_e( 'Saloon Booking', 'quick-table-booking' ); ?></option>
  </select>
  </td>
  </tr>  

  <?php /* if(get_option('pstb_functionality')=='pstb_table_booking_opt') { ?>

  <tr valign="top">
  <th scope="row">
    <label for="pstb_total_person">
    <?php 
    esc_html_e( 'Total Persons Capacity', 'quick-table-booking' ); 
    ?>      
    </label>
  </th>
  <td><input type="number" id="pstb_total_person" name="pstb_total_person" value="<?php echo get_option('pstb_total_person'); ?>" class="pstb-back-field" /></td>
  </tr> 

  <?php } */ ?>

  <tr valign="top">
  <th scope="row">
  	<label for="pstb_holidays">
    <?php 
        esc_html_e( 'Holidays', 'quick-table-booking' ); 
    ?> 
    </label>  	
  </th>
  <td><textarea  id="pstb_holidays" name="pstb_holidays" class="pstb-back-field" ><?php echo get_option('pstb_holidays'); ?></textarea></td>
  </tr>  
  <tr>
    <td colspan="2" >
    <p class="pstb-instr">
      <?php 
          esc_html_e('Format should be dd-mm-yyyy with comma , seperated values', 'quick-table-booking' );  
      ?>      
    </p>   
    </td>
  </tr>
  <tr valign="top">
  <th scope="row">
  	<label for="pstb_week_off"><?php esc_html_e( 'Week Off', 'quick-table-booking' ); ?> </label>  	
  </th>
  <td><select  id="pstb_week_off" name="pstb_week_off[]" multiple="multiple" class="pstb-back-field">  	
  	<?php 

    $pstb_week_off = get_option('pstb_week_off'); 

    if($pstb_week_off != ''){
    ?>
  	<option value="" ><?php esc_html_e( 'select', 'quick-table-booking' ); ?></option> 	
    <?php 
        for($i=0;$i<=6;$i++){ 
    ?>        
            <option value="<?php echo $i; ?>" <?php if (in_array($i, $pstb_week_off)){ echo 'selected="selected"'; } ?> ><?php echo pstb_get_day($i); ?></option>
    <?php
        }
      } 
   ?>
  </select>  
  </td>
  </tr>
  <tr valign="top">
  <th scope="row">
    <label for="pstb_start_time"><?php esc_html_e( 'Start Time', 'quick-table-booking' ); ?></label>    
  </th>
  <td>
    <?php $pstb_start_time = get_option('pstb_start_time'); ?> 
    <select name="pstb_start_time" id="pstb_start_time" class="pstb-back-field" >
    <?php 
    $pstb_start_time_def = "12-00 AM" ;
    $pstb_end_time_def = "11-30 PM" ;
   

       $a = DateTime::createFromFormat("g-i A", $pstb_start_time_def);
       $a = $a->getTimestamp();

       $b = DateTime::createFromFormat("g-i A", $pstb_end_time_def);
       $b = $b->getTimestamp();
      
       $t=$a;
       while($t<=$b){
        $time = date("g-i A",$t);  
        
        $selected = '';
        if($pstb_start_time == $time){ 
            $selected = "selected=selected"; 
        }
        echo '<option value="'.$time.'" '.$selected.'>'.$time.'</option>';    
        $t = strtotime('+30 minutes',$t);
    }

    ?>
    </select>
  </td>
  </tr>
  <tr valign="top">
  <th scope="row">
    <label for="pstb_end_time"><?php esc_html_e( 'End Time', 'quick-table-booking' ); ?></label>    
  </th>
  <td>
    <?php 
        $pstb_end_time = get_option('pstb_end_time'); 
    ?>
    <select name="pstb_end_time" id="pstb_end_time" class="pstb-back-field" >
    <?php 
    $pstb_start_time_def = "12-00 AM" ;
    $pstb_end_time_def = "11-30 PM" ;  

       $a = DateTime::createFromFormat("g-i A", $pstb_start_time_def);
       $a = $a->getTimestamp();

       $b = DateTime::createFromFormat("g-i A", $pstb_end_time_def);
       $b = $b->getTimestamp();
      
       $t=$a;
       while($t<=$b){
        $time = date("g-i A",$t);  
        
        $selected = '';
        if($pstb_end_time == $time){ 
            $selected = "selected=selected"; 
        }
        echo '<option value="'.$time.'" '.$selected.'>'.$time.'</option>';    
        $t = strtotime('+30 minutes',$t);
    }

    ?>
    </select>
  </td>
  </tr> 
  <?php  

  if(get_option('pstb_functionality')=='pstb_hotel_room_booking_opt' || get_option('pstb_functionality')=='pstb_table_booking_opt' ){
      
  ?>

  <tr valign="top">
  <th scope="row">
    <label for="hide_till_time"><?php esc_html_e( 'Hide Till Time ?', 'quick-table-booking' ); ?></label>    
  </th>
  <td>
    <?php 
        $hide_till_time = get_option('pstb_hide_till_time'); 
        $checked = '';
        if($hide_till_time=="yes"){
          $checked = "checked=checked";
        }
    ?>
    <input type="checkbox" name="pstb_hide_till_time" id="pstb_hide_till_time" value="yes" <?php echo $checked; ?> />
  </td>
  </tr>

<?php } ?>  

  <tr>
    <td colspan="2">
      <p class="pstb-instr"><?php esc_html_e( 'Note: add shortcode [book_table_form] in any of page to display booking form', 'quick-table-booking' ); ?></p>
    </td>
  </tr>

  </table>
  <?php  submit_button(); ?>
  </form>
  </div>
<?php
 
 } 
 
add_filter( 'pre_update_option_pstb_end_time', 'pstb_validate_option_value_endtime', 10, 2 );

function pstb_validate_option_value_endtime($new_value, $old_value){
    $start_time = get_option('pstb_start_time');    

    $a = DateTime::createFromFormat("g-i A", $start_time);
    $a = $a->getTimestamp();

    $b = DateTime::createFromFormat("g-i A", $new_value);
    $b = $b->getTimestamp();


    if ($a > $b){
        $err_msg = esc_html__( 'End Time should be greater than or equal to Start Time', 'quick-table-booking' );
        wp_die($err_msg);
    }   
    
    return $new_value;
}