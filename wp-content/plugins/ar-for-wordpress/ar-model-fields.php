<?php
/**
 * AR Display
 * https://augmentedrealityplugins.com
**/
if (!defined('ABSPATH'))
    exit;

/* =========================================================================================== */

if (!function_exists('ar_wp_advance_update_edit_form')){
    add_action('post_edit_form_tag', 'ar_wp_advance_update_edit_form');
    function ar_wp_advance_update_edit_form() {
        echo ' enctype="multipart/form-data"';
    }
}
// end update_edit_form

if (!function_exists('ar_wp_advance_the_upload_metabox')){
    add_action('add_meta_boxes', 'ar_wp_advance_the_upload_metabox');
    
    function ar_wp_advance_the_upload_metabox() {
        // Define the custom attachment for posts  
        add_meta_box('ar_wp_advance_custom_attachment', __( 'Augmented Reality Models', 'ar-for-wordpress' ), 'ar_wp_advance_custom_file_attachment', "armodels", "normal", "high", null);
    }
}
// The custom file attachment function
if (!function_exists('ar_wp_advance_custom_file_attachment')){
    function ar_wp_advance_custom_file_attachment() {
        global $wpdb, $post, $shortcode_examples, $ar_whitelabel, $ar_css_styles, $ar_css_names;
        $plan_check = get_option('ar_licence_plan');
        //Hide the post content area
        ?>
        <style>
            .postarea{display:none;}
            
        </style>
          <div id="ardisplay_panel" class="panel woocommerce_options_panel">
            <div class="options_group">
                
        <div id="ar_shortcode_instructions" style="float:left;vertical-align:bottom">
            <div style="width:50%;float:left;">
                
              <?php  
                echo '<img src="'.esc_url( plugins_url( "assets/images/Ar_logo.png", __FILE__ ) ).'" height="120" style="padding: 0 30px 50px 0;" align="left">';
        
            if ((substr(get_option('ar_licence_valid'),0,5)!='Valid')AND((!get_post_meta($post->ID, '_usdz_file', true ))AND(!get_post_meta($post->ID, '_usdz_file', true )))){
                echo '<b><a href="edit.php?post_type=armodels&page">';
                _e( 'Please check your subscription & license key.</a> If you are using the free version of the plugin then you have exceeded the limit of allowed models.', 'ar-for-wordpress' );
                echo '</b></div></div></div>';
            }else{
                $model_array=array();
                $model_array['id'] = $post->ID;
        ?>
        		<b><input id="ar_shortcode" type="text" value="[ardisplay id=<?=$post->ID;?>]" readonly style="width:150px" onclick="copyToClipboard('ar_shortcode');document.getElementById('copied').innerHTML='&nbsp;Copied!';"></b> <span id="copied">Click to copy</span>
        		<br><?php _e( 'Please place this shortcode on your page where you would like the model displayed.', 'ar-for-wordpress' );?>
        		<br><br><?php _e( 'Models can be uploaded as a USDZ or REALITY file for iOS, and a GLB or GLTF file for viewing on Android devices and the broswer display. The following formats can be uploaded and will be automatically converted to GLB format - DAE, DXF, 3DS, OBJ, PDF, PLY, STL, or Zipped versions of these files. Model conversion accuracy cannot be guaranteed, please check your model carefully.', 'ar-for-wordpress' );?>
                <?php if (!$ar_whitelabel){
        		    echo '<a href="https://augmentedrealityplugins.com/support/#3d" target="_blank">'.__('Sample 3D Models', 'ar-for-wordpress').'</a> <a href="https://augmentedrealityplugins.com/support/#hdr" target="_blank">'.__('Sample HDR Images', 'ar-for-wordpress').'</a> ';
        		}?>
            </div>
            <div style="width:50%;float:left">
                <?php echo $shortcode_examples;?>
            </div>
        </div>
        
        <div style="clear:both"></div>
            <hr>
            <div style="clear:both">
        	<div class="ar_admin_label"><label for="_glb_file"><img src="<?= esc_url( plugins_url( "assets/images/android.png", __FILE__ ) );?>" style="height:20px; padding-right:10px; vertical-align: middle; "><?php _e( 'GLB/GLTF 3D Model', 'ar-for-wordpress' );?></label> </div>
        	<div class="ar_admin_field"><input type="url" pattern="https?://.+" title="<?php _e('Secure URLs only','ar-for-wordpress'); ?> https://" placeholder="https://" name="_glb_file" id="_glb_file" class="regular-text" value="<?php echo get_post_meta( $post->ID, '_glb_file', true );?>"> <input id="upload_usdz_button" class="button" type="button" value="<?php _e( 'Upload AR Files', 'ar-for-wordpress' );?>" /> <a href="#" onclick="document.getElementById('_glb_file').value = ''"><img src="<?=esc_url( plugins_url( "assets/images/delete.png", __FILE__ ) );?>" style="width: 15px;vertical-align: middle;"></a></div>
            
            <div style="clear:both">
        	<div class="ar_admin_label"><label for="_usdz_file"><img src="<?= esc_url( plugins_url( "assets/images/ios.png", __FILE__ ) );?>" style="height:20px; padding-right:10px; vertical-align: middle; "><?php _e( 'USDZ/REALITY 3D Model', 'ar-for-wordpress' );?></label> </div>
        	<div class="ar_admin_field"><input type="url" pattern="https?://.+" title="<?php _e('Secure URLs only','ar-for-wordpress'); ?> https://" placeholder="https://" name="_usdz_file" id="_usdz_file" class="regular-text" value="<?php echo get_post_meta( $post->ID, '_usdz_file', true );?>"> <input id="upload_usdz_button" class="button" type="button" value="<?php _e( 'Upload AR Files', 'ar-for-wordpress' );?>" /> <a href="#" onclick="document.getElementById('_usdz_file').value = ''"><img src="<?=esc_url( plugins_url( "assets/images/delete.png", __FILE__ ) );?>" style="width: 15px;vertical-align: middle;"></a></div>
            
            <br style="clear:both"><?php 
            if($plan_check!='Premium') { 
        		    echo '<b>'.__('Premium Plans Only', 'ar-for-wordpress').'</b><hr>'; 
        		    $disabled = ' disabled';
        		    $readonly = ['readonly' => 'readonly'];
        		    $custom_attributes = $readonly;
        		    echo '<div style="pointer-events: none;">'; //disable mouse clicking 
        		}else{
        		    $disabled = '';
        		    $readonly = '';
        		    //Used for Scale inputs
        		    $custom_attributes = array(
                        'step' => '0.1',
                        'min' => '0.1');
        		}
        		?>
            <div style="clear:both">
            <div class="ar_admin_label"><label for="_skybox_file"><?php _e( 'Skybox/Background Image', 'ar-for-wordpress' ); echo "<br>"; _e('<span class="ar_label_tip">(HDR, JPG or PNG)</span>', 'ar-for-wordpress' );?></label> </div>
        	<div class="ar_admin_field"><input type="url" pattern="https?://.+" title="<?php _e('Secure URLs only','ar-for-wordpress');?> https://" placeholder="https://" name="_skybox_file" id="_skybox_file" class="regular-text" value="<?php echo get_post_meta( $post->ID, '_skybox_file', true );?>" <?php echo $disabled;?>> <input id="upload_skybox_button" class="button" type="button" value="<?php _e( 'Upload Skybox File', 'ar-for-wordpress' );?>"  <?php echo $disabled;?>/> <a href="#" onclick="document.getElementById('_skybox_file').value = ''"><img src="<?=esc_url( plugins_url( "assets/images/delete.png", __FILE__ ) );?>" style="width: 15px;vertical-align: middle;"></a></div>
            <div style="clear:both">
            <div class="ar_admin_label"><label for="_ar_environment"><?php _e( 'Environment Image', 'ar-for-wordpress' ); echo "<br>"; _e('<span class="ar_label_tip">(HDR, JPG or PNG)</span>', 'ar-for-wordpress' );?></label></div>
            <div class="ar_admin_field"><input type="url" pattern="https?://.+" title="<?php _e('Secure URLs only','ar-for-wordpress'); ?> https://" placeholder="https://" name="_ar_environment" id="_ar_environment" class="regular-text" value="<?php echo get_post_meta( $post->ID, '_ar_environment', true );?>" <?php echo $disabled;?>> <input id="upload_environment_button" class="button" type="button" value="<?php _e( 'Upload Environment File', 'ar-for-wordpress' );?>" <?php echo $disabled;?>/> <a href="#" onclick="document.getElementById('_ar_environment').value = ''"><img src="<?=esc_url( plugins_url( "assets/images/delete.png", __FILE__ ) );?>" style="width: 15px;vertical-align: middle;"></a></div>
        	<div style="clear:both">
        	    
        	    
        	<div class="ar_admin_viewer">
                <div class="ar_admin_label"><label for="_ar_placement"><?php _e( 'Model Placement', 'ar-for-wordpress' );?></label></div>
            	<div class="ar_admin_field"><select name="_ar_placement" id="_ar_placement" <?php echo $disabled;?>>
            			<option value="floor" <?php selected( get_post_meta( $post->ID, '_ar_placement', true ), 'floor' ); ?>><?php _e( 'Floor - Horizontal', 'ar-for-wordpress' );?></option>
            			<option value="wall" <?php selected( get_post_meta( $post->ID, '_ar_placement', true ), 'wall' ); ?>><?php _e( 'Wall - Vertical', 'ar-for-wordpress' );?></option>
            	</select></div>
            	<div style="clear:both"></div>
            	<div class="ar_admin_label"><label><?php _e( 'Scale', 'ar-for-wordpress' );?></label><br><span class="ar_label_tip"><?php _e( '1 = 100%, only affects desktop view, not available in AR', 'ar-for-wordpress' );?></span></div>
            	<?php
            	$ar_x = 1;
            	$ar_y = 1;
            	$ar_z = 1;
            	if (get_post_meta( $post->ID, '_ar_x', true )){
            	    $ar_x = get_post_meta( $post->ID, '_ar_x', true );
            	}
            	if (get_post_meta( $post->ID, '_ar_y', true )){
            	    $ar_y = get_post_meta( $post->ID, '_ar_y', true );
            	}
            	if (get_post_meta( $post->ID, '_ar_z', true )){
            	    $ar_z = get_post_meta( $post->ID, '_ar_z', true );
            	}
            	?>
                <div class="ar_admin_field">X: <input id="_ar_x" name="_ar_x" type="number" style="width: 60px;" value="<?php echo $ar_x;?>" size="3" step="0.1" min="0.1" <?php echo $disabled;?>>
                  Y: <input id="_ar_y" name="_ar_y" type="number" style="width: 60px;" value="<?php echo $ar_y;?>" size="3" step="0.1" min="0.1" <?php echo $disabled;?>> 
                  Z: <input id="_ar_z" name="_ar_z" type="number" style="width: 60px;" value="<?php echo $ar_z;?>" size="3" step="0.1" min="0.1" <?php echo $disabled;?>>
                </div>
                <div style="clear:both"></div>
                
                <div class="ar_admin_label"><label for="_ar_field_of_view"><?php _e( 'Field of View', 'ar-for-wordpress' );?></label></div>
                <?php 
                $ar_field_of_view = get_post_meta( $post->ID, '_ar_field_of_view', true );
                $ar_zoom_out = get_post_meta( $post->ID, '_ar_zoom_out', true );
            	$ar_zoom_in = get_post_meta( $post->ID, '_ar_zoom_in', true );?>
            	<div class="ar_admin_field"><select name="_ar_field_of_view" id="_ar_field_of_view" <?php echo $disabled;?>>
                  <option value=""><?php _e('Default','ar-for-wordpress');?></option>
                  <?php 
                  for ($x = 10; $x <= 180; $x+=10) {
                      echo '<option value="'.$x.'"';
                      if ($x==$ar_field_of_view){echo ' selected';}
                      echo '>'.$x.' ';
                      _e( 'Degrees', 'ar-for-wordpress' );
                      echo '</option>';
                  }
                  ?>
                </select>
                </div>
                <div style="clear:both"></div>
                <div class="ar_admin_label"><label for="_ar_zoom"><?php _e( 'Zoom Restraints', 'ar-for-wordpress' );?></label></div>
                
                <?php _e('In', 'ar-for-wordpress');?> <select name="_ar_zoom_in" id="_ar_zoom_in" <?php echo $disabled;?>>
                  <option value="default" <?php if (($ar_zoom_in == 'default')OR($ar_zoom_in == '')){echo 'selected';}?>><?php _e('Default', 'ar-for-wordpress');?></option>
                  <?php 
                  for ($x = 100; $x >= 0; $x-=10) {
                      echo '<option value="'.$x.'"';
                      if (($x==$ar_zoom_in)AND($ar_zoom_in != '')){echo ' selected';}
                      echo '>'.$x.'%</option>';
                  }
                  ?>
                </select>
                <?php _e('Out', 'ar-for-wordpress');?> <select name="_ar_zoom_out" id="_ar_zoom_out" <?php echo $disabled;?>>
                  <option value="default" <?php if (($ar_zoom_out == 'default')OR($ar_zoom_out == '')){echo 'selected';}?>><?php _e('Default', 'ar-for-wordpress');?></option>
                  <?php 
                  for ($x = 0; $x <= 100; $x+=10) {
                      echo '<option value="'.$x.'"';
                      if (($x==$ar_zoom_out)AND($ar_zoom_out != '')){echo ' selected';}
                      echo '>'.$x.'%</option>';
                  }
                  ?>
                </select>
                <div style="clear:both"></div>
                <?php $ar_exposure = get_post_meta( $post->ID, '_ar_exposure', true );
                if (!$ar_exposure){ $ar_exposure = 1; } ?>
                <div class="ar_admin_label"><label for="_ar_exposure"><?php _e( 'Exposure', 'ar-for-wordpress' );?></label></div>
            	<div class="ar_admin_field"><input id="_ar_exposure" name="_ar_exposure" type="range" min="0" max="2" step=".1" value="<?php echo $ar_exposure; ?>" <?php echo $disabled;?> oninput="this.nextElementSibling.value = this.value"> <output><?php echo $ar_exposure; ?></output></div>
            	<div style="clear:both"></div>
                <?php $ar_shadow_intensity = get_post_meta( $post->ID, '_ar_shadow_intensity', true );
                if (!$ar_shadow_intensity){ $ar_shadow_intensity = 1; } ?>
                <div class="ar_admin_label"><label for="_ar_shadow_intensity"><?php _e( 'Shadow Intensity', 'ar-for-wordpress' );?></label></div>
            	<div class="ar_admin_field"><input id="_ar_shadow_intensity" name="_ar_shadow_intensity" type="range" min="0" max="2" step=".1" value="<?php echo $ar_shadow_intensity; ?>" <?php echo $disabled;?> oninput="this.nextElementSibling.value = this.value"> <output><?php echo $ar_shadow_intensity; ?></output></div>
                <div style="clear:both"></div>
                <?php $ar_shadow_softness = get_post_meta( $post->ID, '_ar_shadow_softness', true );
                if (!$ar_shadow_softness){ $ar_shadow_softness = 1; } ?>
                <div class="ar_admin_label"><label for="_ar_shadow_softness"><?php _e( 'Shadow Softness', 'ar-for-wordpress' );?></label></div>
            	<div class="ar_admin_field"><input id="_ar_shadow_softness" name="_ar_shadow_softness" type="range" min="0" max="1" step=".1" value="<?php echo $ar_shadow_softness; ?>" <?php echo $disabled;?> oninput="this.nextElementSibling.value = this.value"> <output><?php echo $ar_shadow_softness; ?></output></div>
                <div style="clear:both"></div>
                
                <?php 
                //Checkbox Field Array
                $field_array = array('_ar_rotate' => 'Disable Interaction Prompt', '_ar_variants' => 'Model includes variants', '_ar_environment_image' => 'Legacy lighting', '_ar_resizing' => 'Resizing - Disable in AR', '_ar_view_hide' => 'AR View - Hide', '_ar_qr_hide' => 'QR Code - Hide', '_ar_animation' => 'Animation - Play/Pause button', '_ar_autoplay' => 'Animation - Auto Play');
                foreach ($field_array as $field => $title){
                ?>
                    <div class="ar_admin_label"><label for="<?php echo $field?>"><?php _e( $title, 'ar-for-wordpress' );?> </label> </div>
            	    <div class="ar_admin_field"><input type="checkbox" name="<?php echo $field?>" id="<?php echo $field?>" class="regular-text" value="1" <?php if (get_post_meta( $post->ID, $field, true )=='1'){echo 'checked';} echo $disabled;?>></div>
                    <div style="clear:both"></div>
                <?php } ?>
            	
                <div style="clear:both"></div>
                <div class="ar_admin_label"><label for="_ar_cta"><?php _e( 'Call To Action Button', 'ar-for-wordpress' ); ?></label><br><span class="ar_label_tip"><?php _e( 'Button Displays in 3D Model view and in AR view on Android only', 'ar-for-wordpress' );?></span></div>
                <div class="ar_admin_field"><input type="text" name="_ar_cta" id="_ar_cta" class="regular-text" value="<?php echo get_post_meta( $post->ID, '_ar_cta', true );?>" <?php echo $disabled;?> style="width:140px;" > </div>
                <div style="clear:both"></div>
                <div class="ar_admin_label"><label for="_ar_cta_url"><?php _e( 'Call To Action URL', 'ar-for-wordpress' ); ?></label></div>
                <div class="ar_admin_field"><input type="url" pattern="https?://.+" name="_ar_cta_url" id="_ar_cta_url" class="regular-text" value="<?php echo get_post_meta( $post->ID, '_ar_cta_url', true );?>" <?php echo $disabled;?> > </div>
            	
                <div class="ar_admin_label"><label for="_ar_hotspot_text"><?php _e( 'Hotspots', 'ar-for-wordpress' );?></label><br><span class="ar_label_tip"><?php _e( 'Add your text, click the Add Hotspot button, then click on your model where you would like it placed', 'ar-for-wordpress' );?></span></div>
            	<div class="ar_admin_field"><input type="text" name="_ar_hotspot_text" id="_ar_hotspot_text" class="regular-text" style="width:140px;" placeholder="<?php _e( 'Hotspot Text', 'ar-for-wordpress' );?>" <?php echo $disabled;?>>
                	<input type="checkbox" name="_ar_hotspot_check" id="_ar_hotspot_check" class="regular-text" value="y" style="display:none;">
                	<input type="button" class="button" onclick="enableHotspot()" value="<?php _e( 'Add Hotspot', 'ar-for-wordpress' );?>" <?php echo $disabled;?>>
                </div>
                
            	<div style="clear:both"></div>
            	<?php 
            	if (get_post_meta( $post->ID, '_ar_hotspots', true )){
            	    $_ar_hotspots = get_post_meta( $post->ID, '_ar_hotspots', true );
            	    $hotspot_count = count($_ar_hotspots['annotation']);
            	    $hide_remove_btn = '';
            	    foreach ($_ar_hotspots['annotation'] as $k => $v){
            	        echo '<div id="_ar_hotspot_container_'.$k.'"><div class="ar_admin_label"><label for="_ar_animation">Hotspot '.$k.'</label></div><div class="ar_admin_field" id="_ar_hotspot_field_'.$k.'">
            	        <input hidden="true" id="_ar_hotspots[data-normal]['.$k.']" name="_ar_hotspots[data-normal]['.$k.']" value="'.$_ar_hotspots['data-normal'][$k].'">
            	        <input hidden="true" id="_ar_hotspots[data-position]['.$k.']" name="_ar_hotspots[data-position]['.$k.']" value="'.$_ar_hotspots['data-position'][$k].'">
            	        <input type="text" class="regular-text hotspot_annotation" id="_ar_hotspots[annotation]['.$k.']" name="_ar_hotspots[annotation]['.$k.']" hotspot_name="hotspot-'.$k.'" value="'.$v.'">
            	        </div></div><div style="clear:both"></div>';
            	    
            	    }
            	}else{
            	    $hotspot_count = 0;
            	    $hide_remove_btn = 'style="display:none;"';
            	    echo '<div id="_ar_hotspot_container_0"></div>';
            	}
            	?>
            	<div class="ar_admin_label"><label for="_ar_remove_hotspot"></label></div>
            	<div class="ar_admin_field"><input id="_ar_remove_hotspot" type="button" class="button" <?php echo $hide_remove_btn;?> onclick="removeHotspot()" value="Remove last hotspot" <?php echo $disabled;?>></div>
            	
            	
                <div style="clear:both"></div>
                <h3> <?php
                	    _e('Element Positions and CSS Styles', 'ar-for-wordpress' );
                        if ($disabled!=''){echo ' - '.__('Premium Plans Only', 'ar-for-wordpress');}
                        ?></h3>
                <div style="clear:both"></div>
                <div class="ar_admin_label"><label for="_ar_animation"><?php _e( 'Override Global Settings', 'ar-for-wordpress' );?></label></div>
            	<div class="ar_admin_field"><input type="checkbox" name="_ar_css_override" id="_ar_css_override" class="regular-text" value="1" <?php if (get_post_meta( $post->ID, '_ar_css_override', true )=='1'){echo 'checked';$hide_custom_css='';}else{$hide_custom_css='style="display:none;"';} echo $disabled;?>> </div>
                <div style="clear:both"></div>
                <div id="ar_custom_css_div" <?php echo $hide_custom_css;?>>
                <input type="button" class="button" onclick="importCSS()" value="<?php _e( 'Import Global Settings', 'ar-for-wordpress' );?>" <?php echo $disabled;?>><br  clear="all"><br>
                    <div class="ar_admin_viewer">
                        <?php //CSS Positions
                        $ar_css_positions = get_post_meta( $post->ID, '_ar_css_positions', true );
                        foreach ($ar_css_names as $k => $v){
                            ?>
                            <div>
                              <div style="width:160px;float:left;"><strong>
                                  <?php _e($k, 'ar-for-wordpress' );?> </strong></div>
                              <div style="float:left;"><select id="_ar_css_positions[<?=$k;?>]" name="_ar_css_positions[<?=$k;?>]" <?= $disabled;?>>
                                  <option value="">Default</option>
                                  <?php 
                                  foreach ($ar_css_styles as $pos => $css){
                                    echo '<option value = "'.$pos.'"';
                                    if (is_array($ar_css_positions)){
                                        if ($ar_css_positions[$k]==$pos){echo ' selected';}
                                    }
                                    echo '>'.$pos.'</option>';
                                  }?>
                                  
                                  </select></div>
                            </div>
                            <br  clear="all">
                            <br>
                        <?php
                        }
                        ?>
                    </div>
                    <div class="ar_admin_viewer">
                        <div>
                          <div style="width:160px;float:left;"><strong>
                              <?php
                                $ar_css = get_post_meta( $post->ID, '_ar_css', true );
                                $ar_css_import_global='';
                                if (get_option('ar_css')!=''){
                                    $ar_css_import_global = get_option('ar_css');
                                }
                                $ar_css_import=file_get_contents(esc_url( plugins_url( "assets/css/ar-display-custom.css", __FILE__ ) ));
                          
                        	    _e('CSS Styling', 'ar-for-wordpress' );
                                ?>
                                </strong>
                                </div>
                          <div style="float:left;"><textarea id="_ar_css" name="_ar_css" style="width: 450px; height: 200px;" <?= $disabled;?>><?php echo $ar_css; ?></textarea></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php 
              /* Display the 3D model if it exists */
              if (get_post_meta($model_array['id'], '_glb_file', true )!=''){
                echo '<div class="ar_admin_viewer">';
                echo '<div style="width: 100%; border: 1px solid #f8f8f8;">'.ar_display_shortcode($model_array).'</div>'; 
                $ar_camera_orbit = get_post_meta( $post->ID, '_ar_camera_orbit', true );?>
                
                <button id="downloadPosterToBlob" onclick="downloadPosterToDataURL()" class="button" type="button" style="margin-top:10px">Set Featured Image</button>
                <input type="hidden" id="_ar_poster_image_field" name="_ar_poster_image_field">
                
                <input id="camera_view_button" class="button" type="button" style="float:right;margin-top: 10px" value="<?php _e( 'Set Current Camera View as Initial', 'ar-for-wordpress' );?>" <?php echo $disabled;?> />
                <input id="_ar_camera_orbit" name="_ar_camera_orbit" type="text" value="<?php echo $ar_camera_orbit;?>" style="display:none;"><br clear="all" style="float:right;">
                <?php
                echo '</div>';
              }?>
            <? /* Asset Builder */ ?>
            <div style="clear:both"></div>
            <hr>
            <span href="#" id="asset_builder_button" class="asset_btn"><?php _e( '3D Asset Builder', 'ar-for-wordpress' );?></span>
            <div id="asset_builder" style="display:none">
                <p><?php _e( 'Choose a model below and then upload your texture files.', 'ar-for-wordpress' );?><br><?php _e( 'You may need to refresh your browser once your AR Asset is built to ensure latest texture files are shown.', 'ar-for-wordpress' );?></p>
        	    <input type="hidden" name="_asset_file" id="_asset_file" class="regular-text">
                <? for($i = 0; $i<10; $i++) {
                ?>
                   <p><span id="texture_<?=$i?>" class="nodisplay"><label for="_asset_texture_file_<? echo $i; ?>">
        	        <input type="text" name="_asset_texture_file_<? echo $i; ?>" id="_asset_texture_file_<? echo $i; ?>" class="regular-text"> <input id="upload_asset_texture_button_<? echo $i; ?>" class="button" type="button" value="Texture File" /> <img src="<?=esc_url( plugins_url( "assets/images/delete.png", __FILE__ ) );?>" style="width: 15px;vertical-align: middle;cursor:pointer" onclick="document.getElementById('_asset_texture_file_<? echo $i; ?>').value = ''">
        	        <input type="text" name="_asset_texture_id_<? echo $i; ?>" id="_asset_texture_id_<? echo $i; ?>" class="nodisplay"></span></p>
             
                <? }
                ?><input type="text" name="_asset_texture_flip" id="_asset_texture_flip" class="nodisplay">
                
                <div id="asset_builder_iframe"></div>
            </div>
            <?php
            if($plan_check!='Premium') { 
        	    echo '</div>'; 
        	//close the div that disables mouse clicking 
        	}
        	?>
        </div>
        
      </div>
    
        <?php
            /*Set post content to include AR shortcode*/
        	$post = array('ID'=> $post->ID,
                'post_content' => '[ardisplay id='.$post->ID.']');
            wp_update_post( $post );
        
            //Output Upload Choose AR Model Files Javascript
            echo ar_upload_button_js();
        }
        ?>
        <script>
            document.getElementById('_skybox_file').addEventListener('change', function() {
                var element = document.getElementById("model_<?php echo $model_array['id']; ?>");
                element.setAttribute("skybox-image", this.value);
            });
            document.getElementById('_ar_environment').addEventListener('change', function() {
                var element = document.getElementById("model_<?php echo $model_array['id']; ?>");
                element.setAttribute("environment-image", this.value);
            });
            document.getElementById('_ar_placement').addEventListener('change', function() {
                var element = document.getElementById("model_<?php echo $model_array['id']; ?>");
                if (this.value == 'floor'){
                    element.setAttribute("ar-placement", '');
                }else{
                    element.setAttribute("ar-placement", this.value);
                }
            });
            document.getElementById('_ar_zoom_in').addEventListener('change', function() {
                var element = document.getElementById("model_<?php echo $model_array['id']; ?>");
                if (this.value == 'default'){
                    element.setAttribute("min-camera-orbit", 'auto auto 20%');
                }else{
                    element.setAttribute("min-camera-orbit", 'auto auto '+(100 - this.value) +'%');
                }
            });
            document.getElementById('_ar_zoom_out').addEventListener('change', function() {
                var element = document.getElementById("model_<?php echo $model_array['id']; ?>");
                if (this.value == 'default'){
                    element.setAttribute("max-camera-orbit", 'Infinity auto 300%');
                }else{
                    element.setAttribute("max-camera-orbit", 'Infinity auto '+(((this.value/100)*400)+100) +'%');
                }
            });
            document.getElementById('_ar_field_of_view').addEventListener('change', function() {
                var element = document.getElementById("model_<?php echo $model_array['id']; ?>");
                if (this.value == 'default'){
                    element.setAttribute("field-of-view", '');
                }else{
                    element.setAttribute("field-of-view", this.value +'deg');
                }
            });
            document.getElementById('_ar_environment_image').addEventListener('change', function() {
                var element = document.getElementById("model_<?php echo $model_array['id']; ?>");
                if (document.getElementById("_ar_environment_image").checked == true){
                    element.setAttribute("environment-image", 'legacy');
                }else{
                    element.setAttribute("environment-image", '');
                }
            });
            document.getElementById('_ar_exposure').addEventListener('change', function() {
                var element = document.getElementById("model_<?php echo $model_array['id']; ?>");
                element.setAttribute("exposure", this.value);
            });
            document.getElementById('_ar_shadow_intensity').addEventListener('change', function() {
                var element = document.getElementById("model_<?php echo $model_array['id']; ?>");
                element.setAttribute("shadow-intensity", this.value);
            });
            document.getElementById('_ar_shadow_softness').addEventListener('change', function() {
                var element = document.getElementById("model_<?php echo $model_array['id']; ?>");
                element.setAttribute("shadow-softness", this.value);
            });
    
            const modelViewer = document.querySelector('#model_<?php echo $model_array['id']; ?>');
            modelViewer.addEventListener('camera-change', () => {
                const orbit = modelViewer.getCameraOrbit();
                const orbitString = `${orbit.theta}rad ${orbit.phi}rad ${orbit.radius}m`;
                jQuery(document).ready(function($){
                    $( "#camera_view_button" ).click(function() {
                        document.getElementById("_ar_camera_orbit").style='display:block';
                        document.getElementById("_ar_camera_orbit").value=orbitString;
                    });
                });
            });
            
            document.getElementById('_ar_view_hide').addEventListener('change', function() {
                var element = document.getElementById("ar-button_<?php echo $model_array['id']; ?>");
                if (document.getElementById("_ar_view_hide").checked == true){
                    element.style.display = "none";
                }else{
                    element.style.display = "block";
                }
            });
            
            document.getElementById('_ar_qr_hide').addEventListener('change', function() {
                var element = document.getElementById("ar-qrcode");
                if (document.getElementById("_ar_qr_hide").checked == true){
                    element.style.display = "none";
                }else{
                    element.style.display = "block";
                }
            });
            
            
            [ _ar_x, _ar_y, _ar_z ].forEach(function(element) {
                element.addEventListener('change', function() {
                    var x = document.getElementById('_ar_x').value;
                    var y = document.getElementById('_ar_y').value;
                    var z = document.getElementById('_ar_z').value;
                    const updateScale = () => {
                      modelViewerTransform.scale = x +' '+ y +' '+ z;
                    };
                    updateScale();
                });
            });
            document.getElementById('_ar_animation').addEventListener('change', function() {
                var element = document.getElementById("ar-button-animation");
                if (document.getElementById("_ar_animation").checked == true){
                    element.style.display = "block";
                }else{
                    element.style.display = "none";
                }
            });
             
            
            document.body.addEventListener( 'keyup', function ( event ) {
                //Hotspots update on change 
                if( event.target.id.startsWith('_ar_hotspots' )) {
                    var hotspot_name = event.target.getAttribute("hotspot_name");
                    var hotspot_content = document.getElementById(event.target.getAttribute("hotspot_name")).innerHTML;
                    document.getElementById(hotspot_name).innerHTML='<div class="annotation">'+event.target.value+'</div>';
                };
                //CTA update on change 
                if( event.target.id=='_ar_cta') {
                    document.getElementById("ar-cta-button-container").style="display:block";
                    document.getElementById("ar-cta-button").innerHTML=event.target.value;
                };
            });
            
            //Custom CSS Importing
            function importCSS(){
                var css_content = '<?php if ($ar_css_import_global!=''){ echo ar_encodeURIComponent($ar_css_import_global);}else{echo ar_encodeURIComponent($ar_css_import);}?>';
                document.getElementById('_ar_css').value = decodeURI(css_content);
                <?php 
                $ar_css_positions = get_option('ar_css_positions');
                if (is_array($ar_css_positions)){
                    foreach ($ar_css_positions as $k => $v){
                          echo "document.getElementById('_ar_css_positions[".$k."]').value = '".$v."';
                          ";
                    }
                }
                ?>
            }
            
            document.getElementById('_ar_css_override').addEventListener('change', function() {
                var element = document.getElementById("ar_custom_css_div");
                if (document.getElementById("_ar_css_override").checked == true){
                    element.style.display = "block";
                }else{
                    element.style.display = "none";
                }
            });
            
            //Save screenshot of model
            function downloadPosterToDataURL() {
                const url = modelViewer.toDataURL();
                const a = document.createElement("a");
                document.getElementById("_ar_poster_image_field").value=url;
                var xhr = new XMLHttpRequest();
                var data = new FormData(document.getElementById("post"));
                xhr.open("POST", "<?php 
                $plugins_url = plugins_url();
                echo $plugins_url.'/ar-for-wordpress/ar-add-media.php'; ?>", true);
                xhr.onload = function () {
                    console.log(this.response);
                };
                xhr.onload = function () { 
                    var attachmentID = xhr.responseText; 
                    wp.media.featuredImage.set( attachmentID );
                }
                xhr.send(data);
                return false;
            }
        </script>
        <!-- HOTSPOTS -->
        <!-- The following libraries and polyfills are recommended to maximize browser support -->
        <!-- Web Components polyfill to support Edge and Firefox < 63 -->
        <script src="https://unpkg.com/@webcomponents/webcomponentsjs@2.1.3/webcomponents-loader.js"></script>
        <!-- Intersection Observer polyfill for better performance in Safari and IE11 -->
        <script src="https://unpkg.com/intersection-observer@0.5.1/intersection-observer.js"></script>
        <!-- Resize Observer polyfill improves resize behavior in non-Chrome browsers -->
        <script src="https://unpkg.com/resize-observer-polyfill@1.5.1/dist/ResizeObserver.js"></script>
        <script>
            var hotspotCounter = <?php echo $hotspot_count; ?>;
            function addHotspot(MouseEvent) {
                //var _ar_hotspot_check = document.getElementById('_ar_hotspot_check').value;
                if (document.getElementById("_ar_hotspot_check").checked != true){
                return;
                    
                }
                var inputtext = document.getElementById('_ar_hotspot_text').value;
            
                // if input = nothing then alert error if it isnt then add the hotspot
                if (inputtext == ""){
                    alert("<?php _e( 'Enter hotspot text first, then click the Add Hotspot button.', 'ar-for-wordpress' );?>");
                    return;
                }else{
               
                    const viewer = document.querySelector('#model_<?php echo $model_array['id']; ?>');
                
                    const x = event.clientX;
                    const y = event.clientY;
                    const positionAndNormal = viewer.positionAndNormalFromPoint(x, y);
                    
                    // if the model is not clicked return the position in the console
                    if (positionAndNormal == null) {
                        console.log('no hit result: mouse = ', x, ', ', y);
                        return;
                    }
                    const {position, normal} = positionAndNormal;
                    
                    // create the hotspot
                    const hotspot = document.createElement('button');
                    hotspot.slot = `hotspot-${hotspotCounter ++}`;
                    hotspot.classList.add('hotspot');
                    hotspot.id = `hotspot-${hotspotCounter}`;
                    hotspot.dataset.position = position.toString();
                    if (normal != null) {
                        hotspot.dataset.normal = normal.toString();
                    }
                    viewer.appendChild(hotspot);
                    //console.log('mouse = ', x, ', ', y, positionAndNormal);
                    
                    
                    // adds the text to last hotspot
                    var element = document.createElement("div");
                    element.classList.add('annotation');
                    element.appendChild(document.createTextNode(inputtext));
                    document.getElementById(`hotspot-${hotspotCounter}`).appendChild(element);
                    
                    //Add Hotspot Input fields
                    var hotspot_container = document.getElementById(`_ar_hotspot_container_${hotspotCounter -1}`);
                    hotspot_container.insertAdjacentHTML('afterend', `<div id="_ar_hotspot_container_${hotspotCounter}" style="padding-bottom: 10px"><div class="ar_admin_label"><label for="_ar_animation">Hotspot ${hotspotCounter}</label></div><div class="ar_admin_field" id="_ar_hotspot_field_${hotspotCounter}">`);
                    
                    var hotspot_fields = document.getElementById(`_ar_hotspot_field_${hotspotCounter}`);
                    var inputList = document.createElement("input");
                    inputList.setAttribute('type','text');
                    inputList.setAttribute('class','regular-text hotspot_annotation');
                    inputList.setAttribute('id',`_ar_hotspots[annotation][${hotspotCounter}]`);
                    inputList.setAttribute('name',`_ar_hotspots[annotation][${hotspotCounter}]`);
                    inputList.setAttribute('hotspot_name',`hotspot-${hotspotCounter}`);
                    inputList.setAttribute('value',document.getElementById('_ar_hotspot_text').value);
                    hotspot_fields.insertAdjacentElement('afterend', inputList);
                    
                    var inputList = document.createElement("input");
                    inputList.setAttribute('hidden','true');
                    inputList.setAttribute('id',`_ar_hotspots[data-position][${hotspotCounter}]`);
                    inputList.setAttribute('name',`_ar_hotspots[data-position][${hotspotCounter}]`);
                    inputList.setAttribute('value',hotspot.dataset.position);
                    hotspot_fields.insertAdjacentElement('afterend', inputList);
                    
                    var inputList = document.createElement("input");
                    inputList.setAttribute('hidden','true');
                    inputList.setAttribute('id',`_ar_hotspots[data-normal][${hotspotCounter}]`);
                    inputList.setAttribute('name',`_ar_hotspots[data-normal][${hotspotCounter}]`);
                    inputList.setAttribute('value',hotspot.dataset.normal);
                    hotspot_fields.insertAdjacentElement('afterend', inputList);
                    
                    hotspot_fields.insertAdjacentHTML('afterend', '</div></div>');
                    
                    //Reset hotspot text box and checkbox
                    document.getElementById('_ar_hotspot_text').value = "";
                    document.getElementById("_ar_hotspot_check").checked = false;
                    
                    //Show Remove Hotspot button
                    document.getElementById('_ar_remove_hotspot').style = "display:block;";
                }
            }
            function enableHotspot(){
                var inputtext = document.getElementById('_ar_hotspot_text').value;
                if (inputtext == ""){
                    alert("<?php _e( 'Enter hotspot text first, then click Add Hotspot button.', 'ar-for-wordpress' );?>");
                    return;
                }else{
                    document.getElementById("_ar_hotspot_check").checked = true;
                }
            }
            function removeHotspot(){
                var el = document.getElementById(`_ar_hotspot_container_${hotspotCounter}`);
                var el2 = document.getElementById(`hotspot-${hotspotCounter}`);
                if (el == null){
                    alert("No hotspots to delete");
                }else{
                    hotspotCounter --;
                    el.remove(); // Removes the last added hotspot fields
                    el2.remove(); // Removes the last added hotspot from model
                }
            }
            
        </script>
        
        
        <?php
    }
}

if ((isset($_POST['_glb_file']))or(isset($_POST['_usdz_file']))){
    add_action('save_post', 'save_ar_option_fields'); // Saving the uploaded file details
}
?>
