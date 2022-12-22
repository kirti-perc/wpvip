<?php
/**
 * AR Display
 * https://augmentedrealityplugins.com
**/


/******** AR Model Custom Fields (Save 3D Model Files and Images)************/
require_once(plugin_dir_path(__FILE__). 'ar-model-fields.php');

/********** AR Register Settings  **************/
if (!function_exists('ar_register_settings')){
    function ar_register_settings() {
        add_option( 'ar_licence_key', '');
        register_setting( 'ar_display_options_group', 'ar_licence_key' );
        add_option( 'ar_licence_valid', '');
        register_setting( 'ar_display_options_group', 'ar_licence_valid' );
        add_option( 'ar_licence_plan', '');
        register_setting( 'ar_display_options_group', 'ar_licence_plan' );
        add_option( 'ar_hide_dimensions', '');
        register_setting( 'ar_display_options_group', 'ar_hide_dimensions' );
        add_option( 'ar_wl_file', '');
        register_setting( 'ar_display_options_group', 'ar_wl_file' );
        add_option( 'ar_view_file', '');
        register_setting( 'ar_display_options_group', 'ar_view_file' );
        add_option( 'ar_qr_file', '');
        register_setting( 'ar_display_options_group', 'ar_qr_file' );
        add_option( 'ar_fullscreen_file', '');
        register_setting( 'ar_display_options_group', 'ar_fullscreen_file' );
        add_option( 'ar_hide_qrcode', '');
        register_setting( 'ar_display_options_group', 'ar_hide_qrcode' );
        add_option( 'ar_hide_fullscreen', '');
        register_setting( 'ar_display_options_group', 'ar_hide_fullscreen' );
        add_option( 'ar_hide_arview', '');
        register_setting( 'ar_display_options_group', 'ar_hide_arview' );
        add_option( 'ar_animation', '');
        register_setting( 'ar_display_options_group', 'ar_animation' );
        add_option( 'ar_autoplay', '');
        register_setting( 'ar_display_options_group', 'ar_autoplay' );
        add_option( 'ar_scene_viewer', '');
        register_setting( 'ar_display_options_group', 'ar_scene_viewer' );
        add_option( 'ar_css_positions', '');
        register_setting( 'ar_display_options_group', 'ar_css_positions' );
        add_option( 'ar_css', '');
        register_setting( 'ar_display_options_group', 'ar_css' );
        
    }
}
add_action( 'admin_init', 'ar_register_settings' );

/******* Element Positions *******/
$ar_css_names = array ('AR Button'=> '.ar-button', 'Dimensions'=>'#controls', 'Fullscreen Button'=>'.ar_popup-btn', 'QR Code'=>'.ar-qrcode', 'Thumbnail Slides'=>'.ar_slider', 'Call To Action'=>'.ar_cta_button');
$ar_css_styles = array();
$ar_css_styles['Top Left'] = 'top: 6px !important; bottom: auto !important; left: 6px !important; right: auto !important; margin: 0 !important;';
$ar_css_styles['Top Center'] = 'top: 6px !important; bottom: auto !important; margin: 0 auto !important; left: 0 !important; right: 0 !important;';
$ar_css_styles['Top Right'] = 'top: 6px !important; bottom: auto !important; left: auto !important; right: 6px !important; margin: 0 !important;';
$ar_css_styles['Bottom Left'] = 'top: auto !important; bottom: 6px !important; left: 6px !important; right: auto !important; margin: 0 !important;';
$ar_css_styles['Bottom Center'] = 'top: auto !important; bottom: 6px !important; margin: 0 auto !important; left: 0 !important; right: 0 !important;';
$ar_css_styles['Bottom Right'] = 'top: auto !important; bottom: 6px !important; left: auto !important; right: 6px !important; margin: 0 !important;';

        
/******* Activate plugin *******/
register_activation_hook(__FILE__, 'ar_plugin_activation');
if (!function_exists('ar_plugin_activation')){
    function ar_plugin_activation() {
            wp_schedule_event( time(), 'daily', 'ar_cron' );
            ar_cron();
    }
}

/******* Deactivate plugin *******/
register_deactivation_hook(__FILE__, 'ar_plugin_deactivation');
if (!function_exists('ar_plugin_deactivation')){
    function ar_plugin_deactivation() {
        wp_clear_scheduled_hook( 'ar_cron' );
    }
}

if (($ar_plugin_id=='Wordpress')AND(!isset($ar_wcfm))){
    $shortcode_examples = '<h3 style="margin-top:0px">'.__('Shortcodes', 'ar-for-wordpress' ).'</h3> 
        <b>[ardisplay id=X]</b> - '.__('Displays the 3D model for a given model/post id.', 'ar-for-wordpress' ).'<br>
        <b>[ardisplay id=\'X,Y,Z\']</b> - '.__('Displays the 3D models for multiple comma seperated model/post ids within 1 viewer and thumbnails to select model.', 'ar-for-wordpress' ).'<br>
        <b>[ardisplay cat=X]</b> - '.__('Displays the 3D models for a given category within 1 viewer and thumbnails to select model.', 'ar-for-wordpress' ).'<br>
        <b>[ardisplay cat=\'X,Y,Z\']</b> - '.__('Displays the 3D models for multiple comma seperated category ids within 1 viewer and thumbnails to select model.', 'ar-for-wordpress' ).'<br>
        <b>[ar-view id=X]</b> - '.__('Displays the AR View button for a given model/post id without the need for the 3D Model viewer being displayed.', 'ar-for-wordpress' ).'<br>
        <b>[ar-qr]</b> - '.__('QR Code shortcode display for the page or post the shortcode is added to.</p>', 'ar-for-wordpress' );

}elseif (($ar_plugin_id=='Woocommerce')AND(!isset($ar_wcfm))){
    $woocommerce_featured_image = '<h3 style="margin-top:0px">'.__('Replace product featured image with the AR model', 'ar-for-woocommerce' ).'</h3>'.__('Copy the folder "single-product" found in the AR for Woocommerce plugin "templates" folder to your theme.', 'ar-for-woocommerce' ).'<br><br>';
    $shortcode_examples = '<h3 style="margin-top:0px">'.__('Shortcodes', 'ar-for-woocommerce' ).'</h3> 
        <b>[ardisplay id=X]</b> - '.__('Displays the 3D model for a given model/post id.', 'ar-for-woocommerce' ).'<br>
        <b>[ardisplay id=\'X,Y,Z\']</b> - '.__('Displays the 3D models for multiple comma seperated model/post ids within 1 viewer and thumbnails to select model.', 'ar-for-woocommerce' ).'<br>
        <b>[ardisplay cat=X]</b> - '.__('Displays the 3D models for a given category within 1 viewer and thumbnails to select model.', 'ar-for-woocommerce' ).'<br>
        <b>[ardisplay cat=\'X,Y,Z\']</b> - '.__('Displays the 3D models for multiple comma seperated category ids within 1 viewer and thumbnails to select model.', 'ar-for-woocommerce' ).'<br>
        <b>[ar-view id=X]</b> - '.__('Displays the AR View button for a given model/post id without the need for the 3D Model viewer being displayed.', 'ar-for-woocommerce' ).'<br>
        <b>[ar-qr]</b> - '.__('QR Code shortcode display for the page or post the shortcode is added to.</p>', 'ar-for-woocommerce' );
}

/************* Check Licence Cron *******************/
if (!function_exists('ar_cron')){
    function ar_cron() { 
            $licence_result = ar_licence_check();
            if (substr($licence_result,0,5)=='Valid'){
            	if (substr($licence_result,-7)=='Premium'){
              	  	update_option( 'ar_licence_plan', 'Premium');
              	  	$licence_result='Valid';
           	 	}else{
             		update_option( 'ar_licence_plan', '');
            	}
            	update_option( 'ar_licence_valid', $licence_result);
            }else{
            	update_option( 'ar_licence_plan', '');
            	update_option( 'ar_licence_valid', '');
            }
            
    }
}


/******* Add Css and Js ***********/


if (!function_exists('ar_advance_register_script')){
    function ar_advance_register_script() {
        wp_register_script('jquery_validate', plugins_url('assets/js/jquery-validate-min.js', __FILE__), array('jquery'), '1.3');
        wp_enqueue_script('jquery_validate');
        wp_register_script('js_ardisplay', plugins_url('assets/js/ar-display.js', __FILE__), array('jquery'), '1.3');
        wp_enqueue_script('js_ardisplay');
        wp_register_style('ar_styles', plugins_url('assets/css/ar-display.css',__FILE__), false, '1.0.0', 'all');
        wp_enqueue_style('ar_styles');
    }
}
add_action('init', 'ar_advance_register_script');


/********** AR Licence Check **************/
if (!function_exists('ar_licence_check')){
    function ar_licence_check() {
        global $wpdb;
        $link = 'https://augmentedrealityplugins.com/ar/ar_subscription_status_check.php';
        ob_start();
        $query = $wpdb->get_results("SELECT * FROM $wpdb->postmeta WHERE (meta_key = '_ar_display' AND meta_value = '1')");
        $model_count= count($query);
        $data = array(
            'method'      => 'POST',
            'body'        => array(
            'domain_name' => site_url(),
        	'licence_key' => get_option('ar_licence_key'),
        	'model_count' => $model_count
        ));
        $response = wp_remote_post( $link, $data);
        if (!is_wp_error($response)){return $response['body'];}else{return 'error';}
        ob_flush();
    }
}



/*********** Display the AR Model Viewer ***********/
if (!function_exists('ar_display_model_viewer')){
    function ar_display_model_viewer($model_array){
        $output='';
        if (($model_array['glb_file']!='')OR($model_array['usdz_file']!='')){
            global $wp, $ar_whitelabel, $ar_css_names, $ar_css_styles;
            $model_style='';
            $model_id =  $model_array['model_id'];
            if ($model_array['skybox_file']!=''){
                $model_array['skybox_file']=' skybox-image="'.$model_array['skybox_file'].'"';
            }
            if ($model_array['ar_pop']=='pop'){
                $model_array['model_id'].='_'.$model_array['ar_pop'];
            }
            if ($model_array['ar_resizing']==1){
                $model_array['ar_resizing']=' ar-scale="fixed"';
            }
            if ($model_array['ar_scene_viewer']==1){
                $viewers = 'scene-viewer webxr quick-look';
            }else{
                $viewers = 'webxr scene-viewer quick-look';
            }
            if ($model_array['ar_hide_arview']!=''){
               $model_array['ar_hide_arview'] = ' nodisplay';
               $show_ar='';
            }else{
                $show_ar=' ar ar-modes="'.$viewers.'" ';
            }
            if ($model_array['ar_hide_model']!=''){
               $model_array['ar_hide_model'] = ' style="display:none;"';
               $model_array['ar_hide_arview'] = '';
               $show_ar=' ar ar-modes="'.$viewers.'" ';
            }
            if ($model_array['ar_autoplay']!=''){
                $model_array['ar_autoplay'] = 'autoplay';                
            }
             if ($model_array['ar_field_of_view']!=''){
                $model_array['ar_field_of_view'] = 'field-of-view="'.$model_array['ar_field_of_view'].'deg"';                
            }else{
                $model_array['ar_field_of_view'] = 'field-of-view=""';
            }
            
            
            if (($model_array['ar_zoom_in']!='')AND($model_array['ar_zoom_in']!='default')){
                $model_array['ar_zoom_in'] = 100 - $model_array['ar_zoom_in'];
                $ar_zoom_in_output = 'min-camera-orbit="auto auto '.$model_array['ar_zoom_in'].'%"';                
            }else{
                $ar_zoom_out_output = 'min-camera-orbit="Infinity auto 20%"';
            }
            
            if (($model_array['ar_zoom_out']!='')AND($model_array['ar_zoom_out']!='default')){
                $model_array['ar_zoom_out'] = (($model_array['ar_zoom_out']/100)*400)+100;
                $ar_zoom_out_output = 'max-camera-orbit="Infinity auto '.$model_array['ar_zoom_out'].'%"';                
            }else{
                $ar_zoom_in_output = 'max-camera-orbit="Infinity auto 300%"';
            }
            if ($model_array['ar_exposure']!=''){
                $model_array['ar_exposure'] = 'exposure="'.$model_array['ar_exposure'].'"';                
            }
            if ($model_array['ar_shadow_intensity']!=''){
                $model_array['ar_shadow_intensity'] = 'shadow-intensity="'.$model_array['ar_shadow_intensity'].'"';                
            }
            if ($model_array['ar_shadow_softness']!=''){
                $model_array['ar_shadow_softness'] = 'shadow-softness="'.$model_array['ar_shadow_softness'].'"';                
            }
            if ($model_array['ar_camera_orbit']!=''){
                $model_array['ar_camera_orbit'] = 'camera-orbit="'.$model_array['ar_camera_orbit'].'"';                
            }
            if ($model_array['ar_environment_image']!=''){
                $model_array['ar_environment_image'] = 'environment-image="legacy"';                
            }
            
            //If on the admin page
            global $pagenow;
            $hotspot_js_click ='';
            if (( $pagenow == 'post.php' ) ) {
                // editing a page or product
                $hotspot_js_click = 'onclick="addHotspot()"';
            }
            
            $output='
            <div class="ardisplay_viewer'.$model_array['ar_pop'].'" '.$model_array['ar_hide_model'].'>
                <script type="module" src="https://unpkg.com/@google/model-viewer/dist/model-viewer.min.js"></script>
                <model-viewer '.$hotspot_js_click.' id="model_'.$model_array['model_id'].'" '.$show_ar.'  camera-controls ';
                    if ($model_array['ar_rotate']==true){
                       $output .= ' interaction-prompt="none"  ';
                    }else{
                       $output .= ' auto-rotate ';
                    }
                    
                    $output .= $model_array['ar_placement'].' 
                    ios-src="'.$model_array['usdz_file'].'" 
                    src="'. $model_array['glb_file'].'" 
                    '. $model_array['skybox_file'].'
                    '. $model_array['ar_environment'].'
                    '. $model_array['ar_resizing'].'
                    '. $model_array['ar_field_of_view'].'
                    '. $ar_zoom_in_output.'
                    '. $ar_zoom_out_output.'
                    '. $model_array['ar_camera_orbit'].'
                    '. $model_array['ar_exposure'].'
                    '. $model_array['ar_shadow_intensity'].'
                    '. $model_array['ar_shadow_softness'].'
                    '. $model_array['ar_environment_image'].'
                    poster="'.esc_url( get_the_post_thumbnail_url($model_array['model_id']) ).'"
                    alt="AR Display 3D model" 
                    class="ar-display-model-viewer" 
                    quick-look-browsers="safari chrome" 
                    '.$model_array['ar_autoplay'].'
                    '.$model_style.'>';
                    
                    if ($model_array['ar_animation']==true){
                       $play_btn= esc_url( plugins_url( "assets/images/ar-play-btn.png", __FILE__ ) );
                       $pause_btn= esc_url( plugins_url( "assets/images/ar-pause-btn.png", __FILE__ ) );
                       $animation_btn = $play_btn;
                       $output .= '<div class="ar-animation-btn-container"><button id="animationButton" slot="hotspot-one" data-position="..." data-normal="..." class="ar-button-animation"><img src="'.$animation_btn.'" class="ar-button-animation" id="ar-button-animation"></button></div>';
                    }
                    //$output.='<div class="ar-default-btn-container">';
                    if ($model_array['ar_view_file']==''){
            	        if ($ar_whitelabel!=true){
                            $output.='<button slot="ar-button" class="ar-button ar-button-default '.$model_array['ar_hide_arview'].'" id="ar-button_'.$model_array['model_id'].'"><img id="ar-img_'.$model_array['model_id'].'" src="'.esc_url( plugins_url( "assets/images/ar-view-btn.png", __FILE__ ) ).'" class="ar-button-img"></button>';
            	        }
            	    }else{
                        $output.='<button slot="ar-button" class="ar-button '.$model_array['ar_hide_arview'].'" id="ar-button_'.$model_array['model_id'].'"><img id="ar-img_'.$model_array['model_id'].'" src="'.esc_url( $model_array['ar_view_file'] ).'" class="ar-button-img"></button>';
                    }
                    
            	    //CTA Button
            	    if (($model_array['ar_cta']!='')AND($model_array['ar_cta_url']!='')){
                        $output.='<div class="ar-cta-button-container">
                            <center><a href="'.$model_array['ar_cta_url'].'"><button slot="ar-cta-button" class="ar_cta_button button" id="ar-cta-button">'.$model_array['ar_cta'].'</button></a></center>
                        </div>';
            	    }
                    //Hotspots
                    if ($model_array['ar_hotspots']!=''){
                        foreach ($model_array['ar_hotspots']['annotation'] as $k => $v){
                            $output.='<button slot="hotspot-'.($k-1).'" class="hotspot" id="hotspot-'.$k.'" data-position="'.$model_array['ar_hotspots']['data-position'][$k].'" data-normal="'.$model_array['ar_hotspots']['data-normal'][$k].'"><div class="annotation">'.$v.'</div></button>';
                        }
                    }
                    if ($model_array['ar_hide_qrcode']==''){
                        $qr_logo_image=esc_url( plugins_url( "assets/images/app_logo.png", __FILE__ ) );
                        $ar_wl_logo = get_option('ar_wl_file'); 
                        if ($model_array['ar_qr_file']!=''){
                            $qr_logo_image=$model_array['ar_qr_file'];
                        }elseif ($ar_wl_logo){ //Show Whitelabel url in QR
                            $qr_logo_image=$ar_wl_logo;
                        }

                        $output.='<div class="ar-qrcode-btn-container hide_on_devices">
                        <button id="ar-qrcode" class="ar-qrcode hide_on_devices" onclick="this.classList.toggle(\'ar-qrcode-large\');" style="background-image: url(data:image/png;base64,'.base64_encode(ar_qr_code($qr_logo_image,home_url( $wp->request ))).');"></button>
                        </div>';
                    
                        
                    }else{
                        $output.='<input type="hidden" id="ar-qrcode">';
                    }

                    $output.='<div class="ar-popup-btn-container hide_on_devices">';
                    
                    //Fullscreen option - if not disabled in settings
                    if ($model_array['ar_hide_fullscreen']!='1'){
                        if ($model_array['ar_pop']=='pop'){
                            $output.='<button id="ar_close_'.$model_array['model_id'].'" class="ar_popup-btn hide_on_devices"><img src="'.esc_url( plugins_url( "assets/images/close.png", __FILE__ ) ).'" class="ar-fullscreen_btn-img"></button>';
                        }else{
                            if ($model_array['ar_fullscreen_file']!=''){
                                $ar_fullscreen_image = $model_array['ar_fullscreen_file'];
                            }else{
                                $ar_fullscreen_image = esc_url( plugins_url( "assets/images/fullscreen.png", __FILE__ ) );
                            }
                            
                            $output.='<button id="ar_pop_Btn_'.$model_array['model_id'].'" class="ar_popup-btn hide_on_devices"><img src="'.$ar_fullscreen_image.'" class="ar-fullscreen_btn-img"></button>';
                        }
                    }
                    $output.='</div>';
                    
                if ($model_array['ar_variants']!=''){
                    $output.='<div class="ar_controls"><select id="variant_'.$model_array['model_id'].'"></select></div> ';
                }
                
                /**** Thumbnail Slider ****/
                
                if ($model_array['ar_model_list']!=''){ 
                    $model_array['ar_model_list']=array_filter($model_array['ar_model_list']);
                 	if (count($model_array['ar_model_list'])>1){ 
                        $output.='<div id="ar_slider" class="ar_slider">
                            <div class="ar_slides">';
                                $slide_count = 0;
                                foreach ($model_array['ar_model_list'] as $k =>$v){
                                    //$thumb = get_the_post_thumbnail_url($v);
                                    $slide_count++;
                                    $slide_selected = '';
                                    if ($slide_count=='1'){$slide_selected = 'selected';}
                                    $output.='<button class="ar_slide '.$slide_selected.'" onclick="switchSrc(\'model_'.$model_array['model_id'].'\', model_'.$model_array['model_id'].', \''.get_post_meta($v, '_glb_file', true ).'\', \''.get_post_meta($v, '_usdz_file', true ).'\')" style="background-image: url(\''.esc_url( get_the_post_thumbnail_url($v) ).'\');"></button>
                                    ';
                                }
                              $output.='</div>
                          </div>';
                	}
                }
                     $output.='<input type="hidden" id="src_'.$model_array['model_id'].'" value="'. $model_array['glb_file'].'">';
                
                
                /**** Hide Dimensions ****/
                if ($model_array['ar_hide_dimensions']==''){
                        $output.='
                        <button slot="hotspot-dot+X-Y+Z" class="dot nodisplay" data-position="1 -1 1" data-normal="1 0 0"></button>
                        <button slot="hotspot-dim+X-Y" class="dimension nodisplay" data-position="1 -1 0" data-normal="1 0 0"></button>
                        <button slot="hotspot-dot+X-Y-Z" class="dot nodisplay" data-position="1 -1 -1" data-normal="1 0 0"></button>
                        <button slot="hotspot-dim+X-Z" class="dimension nodisplay" data-position="1 0 -1" data-normal="1 0 0"></button>
                        <button slot="hotspot-dot+X+Y-Z" class="dot nodisplay" data-position="1 1 -1" data-normal="0 1 0"></button>
                        <button slot="hotspot-dim+Y-Z" class="dimension nodisplay" data-position="0 -1 -1" data-normal="0 1 0"></button>
                        <button slot="hotspot-dot-X+Y-Z" class="dot nodisplay" data-position="-1 1 -1" data-normal="0 1 0"></button>
                        <button slot="hotspot-dim-X-Z" class="dimension nodisplay" data-position="-1 0 -1" data-normal="-1 0 0"></button>
                        <button slot="hotspot-dot-X-Y-Z" class="dot nodisplay" data-position="-1 -1 -1" data-normal="-1 0 0"></button>
                        <button slot="hotspot-dim-X-Y" class="dimension nodisplay" data-position="-1 -1 0" data-normal="-1 0 0"></button>
                        <button slot="hotspot-dot-X-Y+Z" class="dot nodisplay" data-position="-1 -1 1" data-normal="-1 0 0"></button>
                        <div id="controls" class="dimension">
                            <label for="show-dimensions_'.$model_array['model_id'].'" style="margin:0px !important;">Dimensions:</label>
                            <input id="show-dimensions_'.$model_array['model_id'].'" type="checkbox" style="cursor: pointer;">
                        </div>
                    ';
                }
            $output.='
                </model-viewer>';
            /* Custom CSS Styling */
            if ($model_array['ar_css_positions']!=''){
                if (is_array($model_array['ar_css_positions'])){
                    $output .= '
                    <style>';
                        foreach($model_array['ar_css_positions'] as $element => $pos){
                            if ($pos != 'Default'){
                                $output .= $ar_css_names[$element].'{'.$ar_css_styles[$pos].'}';
                            }
                        }
                    $output .= '
                    </style>
                    ';  
                }
            }
            if (($model_array['ar_css']!='')AND($model_array['ar_pop']!='pop')){
                $output .= '
                <style>
                    '.$model_array['ar_css'].'
                </style>
                ';               
            }
                /* Javascripts */
                    
                if ($model_array['ar_variants']!=''){
                    $output.='
                    <script>
                        const modelViewerVariants_'.$model_array['model_id'].' = document.querySelector("model-viewer#model_'.$model_array['model_id'].'");
                        const select_'.$model_array['model_id'].' = document.querySelector(\'#variant_'.$model_array['model_id'].'\');
                        
                        modelViewerVariants_'.$model_array['model_id'].'.addEventListener(\'load\', () => {
                          const names_'.$model_array['model_id'].' = modelViewerVariants_'.$model_array['model_id'].'.availableVariants;
                          for (const name of names_'.$model_array['model_id'].') {
                            const option_'.$model_array['model_id'].' = document.createElement(\'option\');
                            option_'.$model_array['model_id'].'.value = name;
                            option_'.$model_array['model_id'].'.textContent = name;
                            select_'.$model_array['model_id'].'.appendChild(option_'.$model_array['model_id'].');
                          }
                        });
                        
                        select_'.$model_array['model_id'].'.addEventListener(\'input\', (event) => {
                          modelViewerVariants_'.$model_array['model_id'].'.variantName = event.target.value;
                        });
                        </script>';
                }
                if ((is_numeric($model_array['ar_x']))AND(is_numeric($model_array['ar_y']))AND(is_numeric($model_array['ar_z']))AND($model_array['ar_pop']!='pop')){
                    $output.='<script>
                    const modelViewerTransform = document.querySelector("model-viewer#model_'.$model_array['model_id'].'");
                    const updateScale = () => {
                      modelViewerTransform.scale = \''.$model_array['ar_x'].' '.$model_array['ar_y'].' '.$model_array['ar_z'].'\';
                    };
                    updateScale();
                    </script>';
                }
                /*Thumbnail slider*/
                if ($model_array['ar_model_list']!=''){ 
                	if (count($model_array['ar_model_list'])>1){ 
                    $output.='<script>
                    //const modelViewerList = document.querySelector("model-viewer");
                      window.switchSrc = (modelid, element, name, usdz) => {
                        var modelViewerList = document.querySelector("#"+modelid);
                        modelViewerList.src = name;
                        modelViewerList.poster = name;
                        modelViewerList.iosSrc = usdz;
                        const slides = document.querySelectorAll(".ar_slide");
                        slides.forEach((element) => {element.classList.remove("selected");});
                        element.classList.add("selected");
                      };
                    
                      document.querySelector(".ar_slider").addEventListener(\'beforexrselect\', (ev) => {
                        // Keep slider interactions from affecting the XR scene.
                        ev.preventDefault();
                      });
                      </script>';
                	}
                }
                if ($model_array['ar_hide_dimensions']==''){
                    
                
                   $output.=' <script type="module">
                      const modelViewer = document.querySelector(\'#model_'.$model_array['model_id'].'\');
                    
                      modelViewer.querySelector(\'#src_'.$model_array['model_id'].'\').addEventListener(\'input\', (event) => {
                        modelViewer.src = event.target.value;
                      });
                      const checkbox = modelViewer.querySelector(\'#show-dimensions_'.$model_array['model_id'].'\');
                      checkbox.addEventListener(\'change\', () => {
                        modelViewer.querySelectorAll(\'button\').forEach((hotspot) => {
                          if ((hotspot.classList.contains(\'dimension\'))||(hotspot.classList.contains(\'dot\'))){
                              if (checkbox.checked) {
                                hotspot.classList.remove(\'nodisplay\');
                              } else {
                                hotspot.classList.add(\'nodisplay\');
                              }
                          }
                          document.getElementById("ar_pop_Btn_'.$model_id.'").classList.remove(\'nodisplay\');
                          document.getElementById("ar_close_'.$model_id.'_pop").classList.remove(\'nodisplay\');
                          document.getElementById("ar-button_'.$model_array['model_id'].'").classList.remove(\'nodisplay\');
                          document.getElementById("ar-qrcode").classList.remove(\'nodisplay\');
                        });
                      });
                    
                      modelViewer.addEventListener(\'load\', () => {
                        const center = modelViewer.getCameraTarget();
                        const size = modelViewer.getDimensions();
                        const x2 = size.x / 2;
                        const y2 = size.y / 2;
                        const z2 = size.z / 2;
                    
                        modelViewer.updateHotspot({
                          name: \'hotspot-dot+X-Y+Z\',
                          position: `${center.x + x2} ${center.y - y2} ${center.z + z2}`
                        });
                    
                        modelViewer.updateHotspot({
                          name: \'hotspot-dim+X-Y\',
                          position: `${center.x + x2} ${center.y - y2} ${center.z}`
                        });
                        modelViewer.querySelector(\'button[slot="hotspot-dim+X-Y"]\').textContent =
                            `${(size.z * 100).toFixed(0)} cm`;
                    
                        modelViewer.updateHotspot({
                          name: \'hotspot-dot+X-Y-Z\',
                          position: `${center.x + x2} ${center.y - y2} ${center.z - z2}`
                        });
                    
                        modelViewer.updateHotspot({
                          name: \'hotspot-dim+X-Z\',
                          position: `${center.x + x2} ${center.y} ${center.z - z2}`
                        });
                        modelViewer.querySelector(\'button[slot="hotspot-dim+X-Z"]\').textContent =
                            `${(size.y * 100).toFixed(0)} cm`;
                    
                        modelViewer.updateHotspot({
                          name: \'hotspot-dot+X+Y-Z\',
                          position: `${center.x + x2} ${center.y + y2} ${center.z - z2}`
                        });
                    
                        modelViewer.updateHotspot({
                          name: \'hotspot-dim+Y-Z\',
                          position: `${center.x} ${center.y + y2} ${center.z - z2}`
                        });
                        modelViewer.querySelector(\'button[slot="hotspot-dim+Y-Z"]\').textContent =
                            `${(size.x * 100).toFixed(0)} cm`;
                    
                        modelViewer.updateHotspot({
                          name: \'hotspot-dot-X+Y-Z\',
                          position: `${center.x - x2} ${center.y + y2} ${center.z - z2}`
                        });
                    
                        modelViewer.updateHotspot({
                          name: \'hotspot-dim-X-Z\',
                          position: `${center.x - x2} ${center.y} ${center.z - z2}`
                        });
                        modelViewer.querySelector(\'button[slot="hotspot-dim-X-Z"]\').textContent =
                            `${(size.y * 100).toFixed(0)} cm`;
                    
                        modelViewer.updateHotspot({
                          name: \'hotspot-dot-X-Y-Z\',
                          position: `${center.x - x2} ${center.y - y2} ${center.z - z2}`
                        });
                    
                        modelViewer.updateHotspot({
                          name: \'hotspot-dim-X-Y\',
                          position: `${center.x - x2} ${center.y - y2} ${center.z}`
                        });
                        modelViewer.querySelector(\'button[slot="hotspot-dim-X-Y"]\').textContent =
                            `${(size.z * 100).toFixed(0)} cm`;
                    
                        modelViewer.updateHotspot({
                          name: \'hotspot-dot-X-Y+Z\',
                          position: `${center.x - x2} ${center.y - y2} ${center.z + z2}`
                        });
                      });
                    </script>';
                                        

                }
                //Animation button
                if ($model_array['ar_animation']==true){
                    $output .= '<script>
                        animationButton.addEventListener(\'click\', () => {
                          if (model_'.$model_array['model_id'].'.paused) {
                            document.getElementById("ar-button-animation").src="'.$play_btn.'";
                            model_'.$model_array['model_id'].'.play();
                          } else {
                            document.getElementById("ar-button-animation").src="'.$pause_btn.'";
                            model_'.$model_array['model_id'].'.pause();
                          }
                        });
                    </script>';
                }
            $output.='
            </div>';
    
        }
        return $output;
    }
}

/************* Model Viewer Short Code Display *******************/
if (!function_exists('ar_display_shortcode')){
    function ar_display_shortcode($atts) {
        global $ar_plugin_id;
        if (get_option('ar_licence_valid')=='Valid'){
            $model_array=array();
            $model_array['ar_model_list']=array();
            $ar_model_list=array();
            /*Category - retrieve list of models*/
            if (isset($atts['cat'])){
                $ar_cat_list=explode(',',$atts['cat']);
                if ($ar_plugin_id=='Wordpress'){
                    $args = array(
                    'post_type' => 'armodels',
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'model_category', //double check your taxonomy name in you db 
                            'field'    => 'id',
                            'terms'    => $ar_cat_list,
                        ),
                        ),   
                    );
                    $the_query = new WP_Query( $args );
                    
                    // The Loop
                    if ( $the_query->have_posts() ) {
                        while ( $the_query->have_posts() ) {
                            $the_query->the_post();
                            $ar_model_list[]= get_the_ID();
                        }
                    } else {
                        // no posts found
                        
                        return 'no models found';
                    }
                }elseif ($ar_plugin_id=='Woocommerce'){
                    foreach ($ar_cat_list as $k=>$v){
                        $ar_model_list_1=array();
                        $ar_model_list_1 = wc_get_term_product_ids( $v, 'product_cat' );
                        $ar_model_list=array_merge($ar_model_list,$ar_model_list_1);
                    }
                }
                foreach (array_unique($ar_model_list) as $k => $v){
                    $model_array['ar_model_list'][] = preg_replace("/[^0-9]/", "",$v);
                }
                if (isset($ar_model_list[0])){
                    $atts['id'] = $ar_model_list[0];
                }else{ return 'no models found';}
            }elseif (isset($atts['id'])){
                $ar_model_list=explode(',',$atts['id']);
                foreach ($ar_model_list as $k => $v){
                    $model_array['ar_model_list'][] = preg_replace("/[^0-9]/", "",$v);
                }
                $atts['id'] = $ar_model_list[0];
            }
            if (isset($atts['ar_hide_model'])){
                $model_array['ar_hide_model'] = $atts['ar_hide_model'];
            }else{
                $model_array['ar_hide_model'] = '';
            }
            $model_array['model_id'] = $atts['id'];
            
            $model_array['usdz_file'] = get_post_meta($atts['id'], '_usdz_file', true );
            $model_array['glb_file'] = get_post_meta($atts['id'], '_glb_file', true );
            $model_array['ar_variants'] = get_post_meta($atts['id'], '_ar_variants', true );
            $model_array['ar_rotate'] = get_post_meta($atts['id'], '_ar_rotate', true );
            $model_array['ar_x'] = get_post_meta($atts['id'], '_ar_x', true );
            $model_array['ar_y'] = get_post_meta($atts['id'], '_ar_y', true );
            $model_array['ar_z'] = get_post_meta($atts['id'], '_ar_z', true );
            $model_array['ar_field_of_view'] = get_post_meta($atts['id'], '_ar_field_of_view', true );
            $model_array['ar_zoom_out'] = get_post_meta($atts['id'], '_ar_zoom_out', true );
            $model_array['ar_zoom_in'] = get_post_meta($atts['id'], '_ar_zoom_in', true );
            $model_array['ar_resizing'] = get_post_meta($atts['id'], '_ar_resizing', true );
            $model_array['ar_view_hide'] = get_post_meta($atts['id'], '_ar_view_hide', true );
            $model_array['ar_autoplay'] = get_post_meta($atts['id'], '_ar_autoplay', true );
            $model_array['ar_animation'] = get_post_meta($atts['id'], '_ar_animation', true );
            $model_array['skybox_file'] = get_post_meta($atts['id'], '_skybox_file', true );
            $model_array['ar_hide_dimensions']=get_option('ar_hide_dimensions');
            $model_array['ar_hide_arview']=get_option('ar_hide_arview');
            $model_array['ar_exposure']=get_post_meta($atts['id'], '_ar_exposure', true );
            $model_array['ar_shadow_intensity']=get_post_meta($atts['id'], '_ar_shadow_intensity', true );
            $model_array['ar_shadow_softness']=get_post_meta($atts['id'], '_ar_shadow_softness', true );
            $model_array['ar_camera_orbit']=get_post_meta($atts['id'], '_ar_camera_orbit', true );
            $model_array['ar_environment_image']=get_post_meta($atts['id'], '_ar_environment_image', true );
            $model_array['ar_hotspots']=get_post_meta($atts['id'], '_ar_hotspots', true );
            if (isset($atts['hide_qr'])){
                $model_array['ar_hide_qrcode']=1;
            }else{
                $model_array['ar_hide_qrcode']=get_option('ar_hide_qrcode');
            }
            $model_array['ar_cta']=get_post_meta($atts['id'], '_ar_cta', true );
            $model_array['ar_cta_url']=get_post_meta($atts['id'], '_ar_cta_url', true );
            $model_array['ar_hide_fullscreen']=get_option('ar_hide_fullscreen');
            $model_array['ar_scene_viewer']=get_option('ar_scene_viewer');
            $model_array['ar_view_file']=get_option('ar_view_file');
            $model_array['ar_qr_file']=get_option('ar_qr_file');
            $model_array['ar_fullscreen_file']=get_option('ar_fullscreen_file');
            $ar_css_override = get_post_meta($atts['id'], '_ar_css_override', true );
            if (($ar_css_override==1) AND (get_post_meta($atts['id'], '_ar_css_positions', true )!='')){
                $model_array['ar_css_positions']=get_post_meta($atts['id'], '_ar_css_positions', true );
            }else{
                $model_array['ar_css_positions']=get_option('ar_css_positions');
            }
            if (($ar_css_override==1) AND (get_post_meta($atts['id'], '_ar_css', true )!='')){
                $model_array['ar_css']=get_post_meta($atts['id'], '_ar_css', true );
            }else{
                $model_array['ar_css']=get_option('ar_css');
            }
            $model_array['ar_pop']='';
            
            if ($model_array['ar_hide_arview']==''){
                if (get_post_meta($atts['id'], '_ar_view_hide', true )!=''){
                    $model_array['ar_hide_arview'] = '1';
                }
            }
             if ($model_array['ar_hide_qrcode']==''){
                if (get_post_meta($atts['id'], '_ar_qr_hide', true )!=''){
                    $model_array['ar_hide_qrcode'] = '1';
                }
            }
            if (isset($model_array['usdz_file']) OR isset($model_array['glb_file'])){
                if (get_post_meta( $atts['id'], '_ar_placement', true )=='wall'){
                    $model_array['ar_placement']='ar-placement="wall"';
                }else{
                    $model_array['ar_placement']='';
                }
                if (get_post_meta( $atts['id'], '_ar_environment', true )){
                    $model_array['ar_environment']='environment-image="'.get_post_meta( $atts['id'], '_ar_environment', true ).'"';
                }else{
                    $model_array['ar_environment']='';
                }
                /*Add https to http urls before displaying*/
                $ar_ssl_urls=array('usdz_file','glb_file','skybox_file','ar_environment');
                foreach ($ar_ssl_urls as $k=>$url){
                    if ( isset( $model_array[$url] ) ) {
                	    if (substr(sanitize_text_field( $model_array[$url] ),0,7)=='http://'){
                	        $model_array[$url] = 'https://'.substr(sanitize_text_field( $model_array[$url] ),7);
                	    }
                	}
                }
                $output = ar_display_model_viewer($model_array);
                
                //Fullscreen option - if not disabled in settings
                if ($model_array['ar_hide_fullscreen']!='1'){
                    $model_array['ar_pop']='pop';
                    $model_array['skybox_file'] = get_post_meta($atts['id'], '_skybox_file', true );
                    $output.='
                    <div id="ar_popup_'.$atts['id'].'" class="ar_popup">
                        <div class="ar_popup-content">
                            '.ar_display_model_viewer($model_array).'
                        </div>
                    </div>
                    <script>
                        var ar_pop_'.$atts['id'].' = document.getElementById("ar_popup_'.$atts['id'].'");
                        var ar_btn_'.$atts['id'].' = document.getElementById("arBtn_'.$atts['id'].'");
                        var ar_close_'.$atts['id'].' = document.getElementById("ar_close_'.$atts['id'].'_pop");
                        ar_pop_Btn_'.$atts['id'].'.onclick = function() {
                          ar_pop_'.$atts['id'].'.style.display = "block";
                        }
                        ar_close_'.$atts['id'].'.onclick = function() {
                          ar_pop_'.$atts['id'].'.style.display = "none";
                        }
                        window.onclick = function(event) {
                          if (event.target == ar_pop_'.$atts['id'].') {
                            ar_pop_'.$atts['id'].'.style.display = "none";
                          }
                        }
                    </script>';
                }
            }
        }else{
            $output = '<a href="https://augmentedrealityplugins.com" target="_blank">';
            if ($ar_plugin_id=='Wordpress'){
                $output .=__('AR Display License Required', 'ar-for-wordpress' );
            }elseif ($ar_plugin_id=='Woocommerce'){
                $output .=__('AR Display License Required', 'ar-for-woocommerce' );
            }
            
            $output .='</a>';
        }
        return $output;
        
    }
    add_shortcode('ardisplay', 'ar_display_shortcode');
}
/* * ************** End ***************** */

/************* AR View Short Code Display *******************/
if (!function_exists('ar_view_shortcode')){
    function ar_view_shortcode($atts) { 
        if (get_option('ar_licence_valid')=='Valid'){
            $logo='';
            $ar_button_default='';
            if (get_option('ar_view_file')==''){
                $logo=esc_url( plugins_url( "assets/images/ar-view-btn.png", __FILE__ ) );
                $ar_button_default=' ar-button-default';
            }else{
                $logo=get_option('ar_view_file');
            }
            if ((!isset($atts))||(!isset($atts['id']))){
                return 'Please include AR model id in shortcode. [ar-view id=X]';
            }else{
                $atts['ar_hide_model']='1';
                return ar_display_shortcode($atts).'<button slot="ar-button" class="ar-button_standalone '.$ar_button_default.'" id="ar-button-standalone" onclick="document.getElementById(\'ar-button_'.$atts['id'].'\').click()"><img id="ar-img_'.$atts['id'].'" src="'.$logo.'" class="ar-button-img"></button>';
            }
        }
    }
    add_shortcode('ar-view', 'ar_view_shortcode');
}


/************* QR Code Short Code Display *******************/
if (!function_exists('ar_qrcode_shortcode')){
    function ar_qrcode_shortcode($atts) { 
        if (get_option('ar_licence_valid')=='Valid'){
            global $wp;
            $qr_logo_image=esc_url( plugins_url( "assets/images/app_logo.png", __FILE__ ) );
                if (get_option('ar_qr_file')!=''){
                    $qr_logo_image=get_option('ar_qr_file');
                }
            return '<button id="ar-qrcode" class="ar-qrcode_standalone hide_on_devices" onclick="this.classList.toggle(\'ar-qrcode-large\');" style="background-image: url(\'data:image/png;base64,'.base64_encode(ar_qr_code($qr_logo_image,home_url( $wp->request ))).'\');"></button>';
        }
    }
    add_shortcode('ar-qr', 'ar_qrcode_shortcode');
}


/************* Upload AR Model Files Javascript *******************/
if (!function_exists('ar_upload_button_js')){
    function ar_upload_button_js() { 
        global $ar_plugin_id;
	$output='
<script>
    jQuery(document).ready(function($){
        
        var custom_uploader;
        
        $(\'#upload_usdz_button, #upload_glb_button, #upload_skybox_button, #upload_environment_button, #upload_asset_texture_button_0, #upload_asset_texture_button_1, #upload_asset_texture_button_2, #upload_asset_texture_button_3, #upload_asset_texture_button_4, #upload_asset_texture_button_5, #upload_asset_texture_button_6, #upload_asset_texture_button_7, #upload_asset_texture_button_8, #upload_asset_texture_button_9\').click(function(e) {
            var button_clicked = event.target.id;
            e.preventDefault();
        
            //If the uploader object has already been created, reopen the dialog
            /*if (custom_uploader) {
                custom_uploader.open();
                return;
            }*/
    
            //Extend the wp.media object
            custom_uploader = wp.media.frames.file_frame = wp.media({
                title: \'';
                if ($ar_plugin_id=='Wordpress'){
                    $output .=__('Choose your AR Files', 'ar-for-wordpress' );
                }elseif ($ar_plugin_id=='Woocommerce'){
                    $output .=__('Choose your AR Files', 'ar-for-woocommerce' );
                }
                
                $output .= '\',
                button: {
                    text: \'';
                if ($ar_plugin_id=='Wordpress'){
                    $output .=__('Choose your AR Files', 'ar-for-wordpress' );
                }elseif ($ar_plugin_id=='Woocommerce'){
                    $output .=__('Choose your AR Files', 'ar-for-woocommerce' );
                }
                
                $output .= '\'
                },
                multiple: true
            });
    
            //When a file is selected, grab the URL and set it as the text field value
            custom_uploader.on(\'select\', function() {
                var attachments = custom_uploader.state().get(\'selection\').map( 
                   function( attachment ) {
                       attachment.toJSON();
                       return attachment;
                  });
                 $.each(attachments, function( index, attachement ) {
                      
                      var fileurl=attachments[index].attributes.url;
                        var filetype = fileurl.substring(fileurl.length - 4, fileurl.length).toLowerCase();
                        //.reality files = lity (last 4 chars)
                        if ((filetype === \'usdz\') || (filetype === \'USDZ\') || (filetype === \'lity\') || (filetype === \'LITY\')){
                            $(\'#_usdz_file\').val(fileurl);
                        }else if ((filetype === \'.glb\')||(filetype === \'gltf\')||(filetype === \'.zip\')||(filetype === \'.dae\')){
                            $(\'#_glb_file\').val(fileurl);
                        }else if ((filetype === \'.hdr\') || (filetype === \'.jpg\') || (filetype === \'.png\')){';
                            
                            //Asset Builder Textures
                            for($i = 0; $i<10; $i++) { 
                            	$output.='
                                if (button_clicked===\'upload_asset_texture_button_'.$i.'\'){
                                    $(\'#_asset_texture_file_'.$i.'\').val(fileurl); 
                                }';
                            }
                            $output.='
                            if (button_clicked===\'upload_skybox_button\'){
                                $(\'#_skybox_file\').val(fileurl);  
                            }
                            if (button_clicked===\'upload_environment_button\'){
                                $(\'#_ar_environment\').val(fileurl);  
                            }
                        }else{
                        ';
                if ($ar_plugin_id=='Wordpress'){
                    $js_alert =__('Invalid file type. Please choose a USDZ, REALITY, GLB, GLTF, ZIP, HDR, JPG, PNG, DAE, DXF, 3DS, OBJ, PLY or STL file.', 'ar-for-wordpress' );
                }elseif ($ar_plugin_id=='Woocommerce'){
                    $js_alert =__('Invalid file type. Please choose a USDZ, REALITY, GLB, GLTF, ZIP, HDR, JPG, PNG, DAE, DXF, 3DS, OBJ, PLY or STL file.', 'ar-for-woocommerce' );
                }
                
                $output .= '
                             alert(\''.$js_alert.'\');
                        }
                 });
     
            });
            //Open the uploader dialog
            custom_uploader.open();
        });  
        
        //Asset Builder
        $( "#asset_builder_button" ).click(function() {
            $( "#asset_builder" ).toggle("slow")
            $("#asset_builder_iframe").html(\'<iframe src="https://augmentedrealityplugins.com/asset_builder/load.php?referrer='.urlencode(get_site_url()).'" style="width:100%;height:500px" id="asset_builder_iframe"></iframe>\');
        });
    });
    
    //List for Events from the Asset Builder iFrame
    var eventMethod = window.addEventListener
			? "addEventListener"
			: "attachEvent";
	var eventer = window[eventMethod];
	var messageEvent = eventMethod === "attachEvent"
		? "onmessage"
		: "message";

	eventer(messageEvent, function (e) {
		if (e.origin !== \'https://augmentedrealityplugins.com\') return;
		if (e.data.substring(0, 5)===\'https\'){
		    document.getElementById(\'_asset_file\').value = e.data;
		    
		}else{
		    //Show texture input fields and update their labels
		    var details = e.data.split(\',\');
		    document.getElementById(\'_asset_texture_flip\').value = \'\';
		    var i;
            for (i = 0; i < 10; i++) {
              var texture = \'texture_\' + i;
              var label = \'texture_label_\' + i;
              var btn = \'upload_asset_texture_button_\' + i;
              var field = \'_asset_texture_file_\' + i;
              var field_id = \'_asset_texture_id_\' + i;
              var element = document.getElementById(texture);
              element.classList.add("nodisplay");
              if(details[i] === undefined){
                  document.getElementById(field).value = \'\';
                  document.getElementById(field_id).value = \'\';
              }else if (details[i] ===\'flip\'){
                  //alert(details[i]);
                  document.getElementById(\'_asset_texture_flip\').value = \'flip\';
              }else{
                  element.classList.remove("nodisplay");
                  
                  var label_contents = details[i].charAt(0).toUpperCase() + details[i].slice(1);
                  label_contents=label_contents.substring(0,(label_contents.length -4));
                  document.getElementById(field_id).value = details[i];
                  document.getElementById(btn).value = label_contents.replace(\'_\',\' \');
              }
            
            }
		}
	});
</script>';
    return $output;
    }
}




/************* Save Custom AR Fields *************/

if (!function_exists('save_ar_option_fields')){
    function save_ar_option_fields( $post_id ) {
        global $ar_plugin_id;
    	$ar_post ='';
    	
    	
    	if ( isset( $_POST['_usdz_file'] ) ) {
    		update_post_meta( $post_id, '_usdz_file', sanitize_text_field( $_POST['_usdz_file'] ) );
    	}
    	if (( isset( $_POST['_glb_file'] ) ) || ( isset( $_POST['_asset_file'] ) )):
        	if (  $_POST['_asset_file'] !='' ){
        	    //Asset Builder overrides the GLB field
                $path_parts = pathinfo(sanitize_text_field( $_POST['_asset_file'] ));
        	}else{
        	    $path_parts = pathinfo(sanitize_text_field( $_POST['_glb_file'] ));
        	}
    	    /***ZIP***/
    	    /***if zip file, then extract it and put gltf into _glb_file***/
    	    $zip_gltf='';
    	    if (strtolower($path_parts['extension'])=='zip'){
    	        WP_Filesystem();
                $upload_dir = wp_upload_dir();
                $destination_path = $upload_dir['path'].'/ar_asset_'.$post_id.'/';
                if ( $_POST['_asset_file'] !='' ){
                    
                    $src_file=$destination_path.'/temp.zip';
                }else{
                    //$destination_path = $upload_dir['path'].'/'.$path_parts['filename'].'/';
                    $src_file=$upload_dir['path'].'/'.$path_parts['basename'];
                }
                //Delete old Asset folder
                if (file_exists($destination_path)) {
                    ar_remove_asset($destination_path);
                }
                //Create new Asset folder
                if (!mkdir($destination_path, 0755, true)) {
                    die('Failed to create folders...');
                }
                
                if (  $_POST['_asset_file'] !='' ){
                    // If the function it's not available, require it.
                    if ( ! function_exists( 'download_url' ) ) {
                        require_once ABSPATH . 'wp-admin/includes/file.php';
                    }
                    
                    //copy zip from asset_builder to local site
                    $src_file = download_url( sanitize_text_field( $_POST['_asset_file'] ) );
                    $unzipfile = unzip_file( $src_file  , $destination_path);
                    unlink($src_file);
                }else{
                    $unzipfile = unzip_file( $src_file, $destination_path);
                }
                if ( $unzipfile ) {
                    //echo 'Successfully unzipped the file! '. sanitize_text_field( $_POST['_asset_file']);       
                } else {
                    if ($ar_plugin_id=='Wordpress'){
                        _e('There was an error unzipping the file.', 'ar-for-wordpress' );
                    }elseif ($ar_plugin_id=='Woocommerce'){
                        _e('There was an error unzipping the file.', 'ar-for-woocommerce' );
                    }      
                }
                	
                if ( $unzipfile ) {
                    $file= glob($destination_path . "/*.gltf");
                    foreach($file as $filew){
                        $path_parts2=pathinfo($filew);
                        if ( $_POST['_asset_file'] !='' ){
                            
                            if (( isset( $_POST['_asset_file'] ) )AND( isset( $_POST['_asset_texture_file_0'] ) )){
                                for($i=0;$i<10;$i++){
                                    if (isset($_POST['_asset_texture_file_'.$i])){
                                        $asset_textures[$i]['newfile']=$_POST['_asset_texture_file_'.$i];
                                        $asset_textures[$i]['filename']=$_POST['_asset_texture_id_'.$i];
                                    }
                                }
                                $flip = $_POST['_asset_texture_flip'];
                	            asset_builder_texture($upload_dir['path'].'/ar_asset_'.$post_id.'/',$path_parts2['basename'],$asset_textures,$flip);
                	        }
                        }else{
                           // $_POST['_glb_file'] = $path_parts['dirname'].'/'.$path_parts['filename'].'/'.$path_parts2['basename'];
                        }
                        $_POST['_glb_file'] = $upload_dir['url'].'/ar_asset_'.$post_id.'/'.$path_parts2['basename'];
                        $zip_gltf='1'; //If set to 1 then ignore the model conversion process below
                    }
                } else {
                    if ($ar_plugin_id=='Wordpress'){
                        _e('There was an error unzipping the file.', 'ar-for-wordpress' );
                    }elseif ($ar_plugin_id=='Woocommerce'){
                        _e('There was an error unzipping the file.', 'ar-for-woocommerce' );
                    }       
                }
    	    }
 
    	    /***Hotspot saving***/
    	    if ( $_POST['_ar_hotspots'] !='' ){
    	        $_ar_hotspots = json_encode($_POST['_ar_hotspots']);
    	    }
    	    /***Model Conversion***/
    	    /***if model file for conversion then convert and put gltf into _glb_file***/
    	    $allowed_files=array('dxf', 'dae', '3ds','obj','pdf','ply','stl','zip');
    	    if ((in_array(strtolower($path_parts['extension']),$allowed_files))AND($zip_gltf=='')){
    	        WP_Filesystem();
                $upload_dir = wp_upload_dir();
                $destination_file = $upload_dir['path'].'/'.$path_parts['filename'].'.glb';;
                $open = fopen( $destination_file, "w" ); 
                $write = fputs( $open,  ar_model_conversion(sanitize_text_field( $_POST['_glb_file'] )) ); 
                fclose( $open );
                $_POST['_glb_file']= $path_parts['dirname'].'/'.$path_parts['filename'].'.glb';
    	    }
    	    
    		update_post_meta( $post_id, '_glb_file', sanitize_text_field( $_POST['_glb_file'] ) );
    	endif;
    	if ((isset( $_POST['_usdz_file'] )) OR( isset($_POST['_glb_file']))){
    	    update_post_meta( $post_id, '_ar_placement', $_POST['_ar_placement'] );
    	    update_post_meta( $post_id, '_ar_display', '1' );
    	}else{
    	    update_post_meta( $post_id, '_ar_display', '' );
    	}
    	$field_array=array('_skybox_file','_ar_environment','_ar_variants','_ar_rotate','_ar_x','_ar_y','_ar_z','_ar_field_of_view','_ar_zoom_out','_ar_zoom_in','_ar_exposure','_ar_camera_orbit','_ar_environment_image','_ar_shadow_intensity','_ar_shadow_softness','_ar_resizing','_ar_view_hide','_ar_qr_hide','_ar_animation','_ar_autoplay','_ar_hotspots','_ar_cta','_ar_cta_url','_ar_css_override','_ar_css_positions','_ar_css');
    	foreach ($field_array as $k => $v){
    	    if ( isset( $_POST[$v] ) ) {
    		    update_post_meta( $post_id, $v, $_POST[$v] );
        	}else{
        	    update_post_meta( $post_id, $v, '');
        	}
    	    
    	}
    	
    	update_post_meta( $post_id, '_ar_shortcode', '[ardisplay id='.$post_id.']');
    	
    }
 }
 
/************* Asset Builder Textures *************/
if (!function_exists('asset_builder_texture')){
    function asset_builder_texture($dir, $gltf,$textures, $flip){
        $gltf_json = json_decode(get_local_file_contents($dir.$gltf),true);
        foreach ($textures as $texture_key=>$texture_array){
            $texture=$texture_array['newfile'];
            $orig_texture_filename=$texture_array['filename'];
            $src_texture_ext = strtolower(substr($texture,-3));
            if (($src_texture_ext=='jpg')||($src_texture_ext=='png')){
                //read gltf file
                
                foreach ($gltf_json['images'] as $k=>$v){
                    if (substr($v['uri'],7)==$orig_texture_filename){
                        $json_texture_ext = substr($v['uri'],-3);
                        //update gltf texture extension if need be.
                        if ($json_texture_ext!=$src_texture_ext){
                            $gltf_json['images'][$k]['uri'] = substr($v['uri'],0,-3).$src_texture_ext;
                            if ($src_texture_ext=='jpg'){
                                $gltf_json['images'][$k]['mimeType']='image/jpeg';
                            }elseif ($src_texture_ext=='png'){
                                $gltf_json['images'][$k]['mimeType']='image/png';
                            }
                            $destination_file = $dir.$gltf;
                            $open = fopen( $destination_file, "w" ); 
                            $write = fputs( $open,  json_encode($gltf_json)); 
                            fclose( $open );
                        }
                    }
                }
                //get name of texture file
                unlink($dir.'images/'.$orig_texture_filename);
                //copy texture file to zip folder and rename it to replace existing texture
                if ($flip=='flip'){
                    $texture = esc_url( plugins_url( "ar_asset_image.php", __FILE__ ) ).'?file='.urlencode($texture);
                }
                copy ($texture,$dir.'images/'.substr($orig_texture_filename,0,-3).$src_texture_ext);
            }
        }
    }
}



/********** AR Upgrade to Premium Version Banner Ribbon **************/
if (!function_exists('ar_admin_notice_upgrade_banner')){
    function ar_admin_notice_upgrade_banner() {
        global $ar_whitelabel;
        $plugin_check = get_option('ar_licence_valid');
        if (($plugin_check!='Valid')AND($ar_whitelabel!=true)){
            ar_upgrade_banner(); 
        }
    }
    add_action( 'admin_notices', 'ar_admin_notice_upgrade_banner' );
}

if (!function_exists('ar_upgrade_banner')){
    function ar_upgrade_banner() { 
        global $ar_plugin_id; 
        
        ?>
        <style>
        #upgrade_ribbon_top {
            position: relative;
            z-index: 2;
        }
        #upgrade_ribbon_left {
            background: url(<?php echo esc_url( plugins_url( "assets/images/ribbon_left.png", __FILE__ ) );?>) no-repeat -11px 0px;
            width: 80px;
            height: 60px;
            float: left;
        }
        #upgrade_ribbon_base {
            background: url(<?php echo esc_url( plugins_url( "assets/images/ribbon_base.png", __FILE__ ) );?>) repeat-x;
            height: 60px;
            margin-left: 49px;
            margin-right: 70px;
        }
        #upgrade_ribbon_right {
            background: url(<?php echo esc_url( plugins_url( "assets/images/ribbon_right.png", __FILE__ ) );?>) no-repeat;
            width: 80px;
            height: 60px;
            position: absolute;
            top: 0px;
            right: -10px;
        }
        #upgrade_ribbon_base span {
            display: inline-block;
            color: white;
            position: relative;
            top: 11px;
            height: 35px;
            line-height: 33px;
            font-size: 17px;
            font-weight: bold;
            font-style: italic;
            text-shadow: 1px 3px 2px #597c2a;
        }
        #upgrade_premium {
            background: rgb(58, 80, 27);
            background: rgba(58, 80, 27, 0.73);
            cursor: pointer;
            padding: 0px 12px;
            margin-left: -17px;
            font-style: normal !important;
            margin-right: 12px;
            text-shadow: 1px 3px 2px #364C18 !important;
        }
        #upgrade_premium a, #upgrade_premium_meta a{color:#fff;text-decoration:none;}
        </style>
        <?php
        echo '<div id="upgrade_ribbon" class="notice notice-warning is-dismissible">
            
            	<div id="upgrade_ribbon_top">
                	<div id="upgrade_ribbon_left">
                    </div>
                    <div id="upgrade_ribbon_base">
                    	<span id="upgrade_premium"><a href="https://augmentedrealityplugins.com" target="_blank">';
                    	if ($ar_plugin_id=='Wordpress'){
                            _e('AR Display', 'ar-for-wordpress' );
                        }elseif ($ar_plugin_id=='Woocommerce'){
                            _e('AR Display', 'ar-for-woocommerce' );
                        }  
                        	
                    	echo '</a></span>
                        <span id="upgrade_premium_meta"><a href="https://augmentedrealityplugins.com" target="_blank">';
                    	if ($ar_plugin_id=='Wordpress'){
                            _e('Upgrade to Premium - Unlimited Models & Full Settings For Only', 'ar-for-wordpress' );
                        }elseif ($ar_plugin_id=='Woocommerce'){
                            _e('Upgrade to Premium - Unlimited Models & Full Settings For Only', 'ar-for-woocommerce' );
                        }  
                        	
                    	echo ' $10!</a></span>
                    </div>
                    <div id="upgrade_ribbon_right">
                    </div>
                </div>
            </div>';
    }        
}            
            
/********** AR 3D Model Conversion **************/
if (!function_exists('ar_model_conversion')){
    function ar_model_conversion($model) {
        $link = 'https://augmentedrealityplugins.com/converters/glb_conversion.php';
        ob_start();
        $response = wp_remote_get( $link.'?model_url='.rawurlencode($model));
        if ( !is_wp_error($response) && isset( $response[ 'body' ] ) ) {
            return $response['body'];
        }
        ob_flush();
    }
 }

/************Allow USDZ mime upload in Wordpress Media Library *****************/
if (!function_exists('ar_my_file_types')){
    function ar_my_file_types($mime_types) { //Add Additional File Types
        $mime_types['usdz'] = 'model/vnd.usdz+zip';
        return $mime_types;
    }
}
add_filter('upload_mimes', 'ar_my_file_types', 1, 1);

if (!function_exists('ar_display_media_library')){
    function ar_display_media_library( $data, $file, $filename, $mimes ) {
    	if ( ! empty( $data['ext'] ) && ! empty( $data['type'] ) ) {
    		return $data;
    	}
    	$registered_file_types = [
    	    'usdz' => 'model/vnd.usdz+zip|application/octet-stream|model/x-vnd.usdz+zip',
    	    'USDZ' => 'model/vnd.usdz+zip|application/octet-stream|model/x-vnd.usdz+zip',
    	    'reality' => 'application/octet-stream',
    	    'REALITY' => 'application/octet-stream',
    	    'glb' => 'model/gltf-binary|application/octet-stream|model',
    	    'GLB' => 'model/gltf-binary|application/octet-stream|model',
    	    'gltf' => 'model/gltf+json',
    	    'GLTF' => 'model/gltf+json',
    	    'hdr' => 'model/gltf+json',
    	    'HDR' => 'model/gltf+json',
    	    'dxf' => 'application/dxf',
    	    'DXF' => 'application/dxf',
    	    'dae' => 'application/dae',
    	    'DAE' => 'application/dae',
    	    '3ds' => 'application/x-3ds',
    	    '3DS' => 'application/x-3ds',
    	    'obj' => 'model/obj',
    	    'OBJ' => 'model/obj',
    	    'ply' => 'application/octet-stream',
    	    'PLY' => 'application/octet-stream',
    	    'stl' => 'model/stl',
    	    'STL' => 'model/stl'
    	    ];
    	$filetype = wp_check_filetype( $filename, $mimes );
    	if ( ! isset( $registered_file_types[ $filetype['ext'] ] ) ) {
    		return $data;
    	}
    	return [
    		'ext' => $filetype['ext'],
    		'type' => $filetype['type'],
    		'proper_filename' => $data['proper_filename'],
    	];
    }
    add_filter( 'wp_check_filetype_and_ext', 'ar_display_media_library', 10, 4 );
}

if (!function_exists('ar_display_mimes')){
    function ar_display_mimes( $mime_types ) {
    	if ( ! in_array( 'usdz', $mime_types ) ) { 
    		$mime_types['usdz'] = 'model/vnd.usdz+zip|application/octet-stream|model/x-vnd.usdz+zip';
    	}
    	if ( ! in_array( 'reality', $mime_types ) ) { 
    		$mime_types['reality'] = 'application/octet-stream';
    	}
    	if ( ! in_array( 'glb', $mime_types ) ) { 
    		$mime_types['glb'] = 'model/gltf-binary|application/octet-stream|model';
    	}
    	if ( ! in_array( 'gltf', $mime_types ) ) { 
    		$mime_types['gltf'] = 'model/gltf+json';
    	}
    	if ( ! in_array( 'hdr', $mime_types ) ) { 
    		$mime_types['hdr'] = 'image/vnd.radiance';
    	}
    	if ( ! in_array( 'dxf', $mime_types ) ) { 
    		$mime_types['dxf'] = 'application/dxf';
    	}
    	if ( ! in_array( 'dae', $mime_types ) ) { 
    		$mime_types['dae'] = 'application/dae';
    	}
    	if ( ! in_array( '3ds', $mime_types ) ) { 
    		$mime_types['3ds'] = 'application/x-3ds';
    	}
    	if ( ! in_array( 'obj', $mime_types ) ) { 
    		$mime_types['obj'] = 'model/obj';
    	}
    	if ( ! in_array( 'ply', $mime_types ) ) { 
    		$mime_types['ply'] = 'application/octet-stream';
    	}
    	if ( ! in_array( 'stl', $mime_types ) ) { 
    		$mime_types['stl'] = 'model/stl';
    	}
    	return $mime_types;
    }
    
    add_filter( 'upload_mimes', 'ar_display_mimes' );
}

/************* AR Custom column *************/
if (!function_exists('ar_advance_custom_armodels_column')){
    function ar_advance_custom_armodels_column( $column, $post_id ) {
        switch ( $column ) { 
            case 'Shortcode' :
                echo '<input id="ar_shortcode_'.$post_id.'" type="text" value="[ardisplay id='.$post_id.']" readonly style="width:150px" onclick="copyToClipboard(\'ar_shortcode_'.$post_id.'\');document.getElementById(\'copied_'.$post_id.'\').innerHTML=\'&nbsp;Copied!\';"><span id="copied_'.$post_id.'"></span>';
                break;
            case 'thumbs' :
                $get_model_check = get_post_meta($post_id, '_usdz_file', true);
                if(empty($get_model_check)){
                  $get_model_check = get_post_meta($post_id, '_glb_file', true);
                }
                if(!empty($get_model_check)){
                $ARimgSrc = esc_url( plugins_url( "assets/images/chair.png", __FILE__ ) );
                $product_link = admin_url( 'post.php?post=' . $post_id ) . '&action=edit#ar_woo_advance_custom_attachment"';
                echo '<a href="'.$product_link.'"><div class="ar_tooltip"><img src="'.$ARimgSrc.'" width="20"><span class="ar_tooltip_text">';
                if ($ar_plugin_id=='Wordpress'){
                    _e('AR Model', 'ar-for-wordpress' );
                }elseif ($ar_plugin_id=='Woocommerce'){
                    _e('AR Model', 'ar-for-woocommerce' );
                } 
                echo '</span></div></a>';
                break;
                }
        }	
    }
}

if (!function_exists('get_local_file_contents')){
    function get_local_file_contents( $file_path ) {
        ob_start();
        include $file_path;
        $contents = ob_get_clean();
    
        return $contents;
    }
}
if (!function_exists('ar_remove_asset')){
    function ar_remove_asset($dir) {
       if (is_dir($dir)) {
         $objects = scandir($dir);
         foreach ($objects as $object) {
           if ($object != "." && $object != "..") {
             if (filetype($dir."/".$object) == "dir") ar_remove_asset($dir."/".$object); else unlink($dir."/".$object);
           }
         }
         reset($objects);
         rmdir($dir);
       }
    }
}

/********** Settings Page **********/
if (!function_exists('ar_subscription_setting')){
    function ar_subscription_setting() {
        global $ar_plugin_id, $shortcode_examples, $woocommerce_featured_image, $ar_whitelabel, $ar_css_styles, $ar_css_names;
        
        if ($_POST){
            //Save Settings
            $settings_fields=array('ar_licence_key','ar_wl_file', 'ar_view_file', 'ar_qr_file', 'ar_fullscreen_file', 'ar_hide_dimensions', 'ar_hide_arview', 'ar_hide_qrcode', 'ar_hide_fullscreen','ar_scene_viewer','ar_css','ar_css_positions');
            foreach ($settings_fields as $k => $v){
                if (!isset($_POST[$v])){$_POST[$v]='';}
                update_option( $v, $_POST[$v]);
            }
        }
    
        $ar_logo = esc_url( plugins_url( 'assets/images/Ar_logo.png', __FILE__ ) ); 
        $ar_wl_logo = get_option('ar_wl_file'); 
        ?>
        <div class="message_set"></div>
      
        <div class="licence_key" id="key" style="float:left;">
            <form method="post" action="edit.php?post_type=armodels&page">
        <?php 
        ar_cron();
        $plugin_check = get_option('ar_licence_valid');
        $plan_check = get_option('ar_licence_plan');
        
        if ($ar_whitelabel!=true){ ?>   
        	<div class="ar_site_logo">
        	    <a href = "https://augmentedrealityplugins.com" target = "_blank">				
        		<img src="<?php echo $ar_logo;?>" style="width:300px; padding-top:20px;float:left" />
        	    </a>
        	</div>
        	<br clear="all">
        	<h1>AR Display</h1>
        	<h3><?php
        	if ($ar_plugin_id=='Wordpress'){
                    _e('Subscription Plan', 'ar-for-wordpress' );
                }elseif ($ar_plugin_id=='Woocommerce'){
                    _e('Subscription Plan', 'ar-for-woocommerce' );
                }
            ?></h3>
        	<br clear="all">
    	<?php }else{
    	//White Label Logo 
    	?>
    	<div>
    	    <?php 
    	    
    	    if ($ar_wl_logo){
    	    ?>
    	    	<div class="ar_site_logo">
            	    			
            		<img src="<?php echo $ar_wl_logo;?>" style="max-width:300px; padding:20px 0px;float:left" />
            	    <input type="hidden" name="ar_wl_file" id="ar_wl_file" class="regular-text" value="<?php echo $ar_wl_logo; ?>">
            	</div>
            	<br clear="all">
        	<?php }
        	if (!get_option('ar_licence_key')){  ?>
                  <div style="width:160px;float:left;"><strong>White Label Logo</strong></div>
                  <div style="float:left;"><input type="text" name="ar_wl_file" id="ar_wl_file" class="regular-text" value="<?php echo $ar_wl_logo; ?>"> <input id="ar_wl_file_button" class="button" type="button" value="White Label Logo File" /> <img src="<?=esc_url( plugins_url( "assets/images/delete.png", __FILE__ ) );?>" style="width: 15px;vertical-align: middle;cursor:pointer" onclick="document.getElementById('ar_wl_file').value = ''"></div>
            <?php } ?>
            </div>
            <br  clear="all">
    	
    	
    	<?php }?>
    	<div class="licence_page">
        
            
              <?php settings_fields( 'ar_display_options_group' ); ?>  
              
              <div>
                  <div style="width:160px;float:left;"><strong>
                      <?php
                	    if ($ar_plugin_id=='Wordpress'){
                            _e('License Key', 'ar-for-wordpress' );
                        }elseif ($ar_plugin_id=='Woocommerce'){
                            _e('License Key', 'ar-for-woocommerce' );
                        }
                        ?></strong></div>
                  <div style="float:left;"><input type="text" id="ar_licence_key" name="ar_licence_key" class="regular-text" style="width:160px" value="<?php echo get_option('ar_licence_key'); ?>" /></div>
                </div>
                <?php 
                $disabled = '';
                
                if($plan_check=='Premium') { 
                    echo '<div style="float:left;margin-top:4px"><span style="color:green;margin-left: 7px; font-size: 19px;">&#10004;</span> '.get_option('ar_licence_plan').'</div>'; 
                } else { 
                    echo '<div style="float:left;margin-top:4px"><span style="color:red;margin-left: 7px; font-size: 19px;">&#10008;</span></div>'; 
                    $disabled =' disabled';
                }
                
                ?>
              <br clear="all"><br>
              <hr>
              
              <h3> <?php
                	    if ($ar_plugin_id=='Wordpress'){
                            _e('Options', 'ar-for-wordpress' );
                            if ($disabled!=''){echo ' - '.__('Premium Plans Only', 'ar-for-wordpress');}
                        }elseif ($ar_plugin_id=='Woocommerce'){
                            _e('Options', 'ar-for-woocommerce' );
                            if ($disabled!=''){echo ' - '.__('Premium Plans Only', 'ar-for-woocommerce');}
                        }
                        ?></h3>
            <p>
        	<div>
                  <div style="width:160px;float:left;"><strong>
                      <?php
                	    if ($ar_plugin_id=='Wordpress'){
                            _e('Custom AR Button', 'ar-for-wordpress' );
                            $ar_logo_file_txt = __('AR Logo File', 'ar-for-wordpress');
                        }elseif ($ar_plugin_id=='Woocommerce'){
                            _e('Custom AR Button', 'ar-for-woocommerce' );
                            $ar_logo_file_txt = __('AR Logo File', 'ar-for-woocommerce');
                        }
                        ?></strong></div>
                  <div style="float:left;"><input type="text" name="ar_view_file" id="ar_view_file" class="regular-text" value="<?php echo get_option('ar_view_file'); ?>" <?= $disabled;?>/> <input id="ar_view_file_button" class="button" type="button" value="<?php echo $ar_logo_file_txt;?>" <?= $disabled;?> /> <img src="<?=esc_url( plugins_url( "assets/images/delete.png", __FILE__ ) );?>" style="width: 15px;vertical-align: middle;cursor:pointer" onclick="document.getElementById('ar_view_file').value = ''"></div>
            </div>
            <br  clear="all">
            <br>
            <div>
                  <div style="width:160px;float:left;"><strong>
                      <?php
                	    if ($ar_plugin_id=='Wordpress'){
                            _e('Custom QR Logo', 'ar-for-wordpress' );
                            $qr_logo_file_txt = __('QR Logo File', 'ar-for-wordpress');
                        }elseif ($ar_plugin_id=='Woocommerce'){
                            _e('Custom QR Logo', 'ar-for-woocommerce' );
                            $qr_logo_file_txt = __('QR Logo File', 'ar-for-woocommerce');
                        }
                        ?></strong><br><?php _e('(JPG file 250 px x 250px)', 'ar-for-wordpress');?></div>
                  <div style="float:left;"><input type="text" name="ar_qr_file" id="ar_qr_file" class="regular-text" value="<?php echo get_option('ar_qr_file'); ?>" <?= $disabled;?>> <input id="ar_qr_file_button" class="button" type="button" value="<?php echo $qr_logo_file_txt;?>" <?= $disabled;?>/> <img src="<?=esc_url( plugins_url( "assets/images/delete.png", __FILE__ ) );?>" style="width: 15px;vertical-align: middle;cursor:pointer" onclick="document.getElementById('ar_qr_file').value = ''"></div>
            </div>
            <br  clear="all">
            <br>
            <div>
                  <div style="width:160px;float:left;"><strong>
                      <?php
                	    if ($ar_plugin_id=='Wordpress'){
                            _e('Custom Fullscreen Button', 'ar-for-wordpress' );
                            $ar_logo_file_txt = __('AR Fullscreen File', 'ar-for-wordpress');
                        }elseif ($ar_plugin_id=='Woocommerce'){
                            _e('Custom Fullscreen Button', 'ar-for-woocommerce' );
                            $ar_logo_file_txt = __('AR Fullscreen File', 'ar-for-woocommerce');
                        }
                        ?></strong></div>
                  <div style="float:left;"><input type="text" name="ar_fullscreen_file" id="ar_fullscreen_file" class="regular-text" value="<?php echo get_option('ar_fullscreen_file'); ?>" <?= $disabled;?>/> <input id="ar_fullscreen_file_button" class="button" type="button" value="<?php echo $ar_logo_file_txt;?>" <?= $disabled;?> /> <img src="<?=esc_url( plugins_url( "assets/images/delete.png", __FILE__ ) );?>" style="width: 15px;vertical-align: middle;cursor:pointer" onclick="document.getElementById('ar_fullscreen_file').value = ''"></div>
            </div>
            <br  clear="all">
            <br>
        	<?php
        	//Global Checkbox Fields 
        	$field_array = array('ar_hide_dimensions' => 'Hide Dimensions', 'ar_hide_arview' => 'Hide AR View', 'ar_hide_qrcode' => 'Hide QR Code', 'ar_hide_fullscreen' => 'Disable Fullscreen', 'ar_scene_viewer' => 'Android - Prioritise Scene Viewer over WebXR');
        	
        	foreach ($field_array as $field => $title){
            ?>
            <div>
              <div style="width:160px;float:left;"><strong>
                  <?php
                	    if ($ar_plugin_id=='Wordpress'){
                            _e($title, 'ar-for-wordpress' );
                        }elseif ($ar_plugin_id=='Woocommerce'){
                            _e($title, 'ar-for-woocommerce' );
                        }
                        ?></strong></div>
              <div style="float:left;"><input type="checkbox" id="<?php echo $field;?>" name="<?php echo $field;?>"  value="1" <?php if (get_option($field)=='1'){echo 'checked'; } ?> <?= $disabled;?>/></div>
            </div>
            <br  clear="all">
            <br>
            <?php } ?>
            
            <h3> <?php
                	    if ($ar_plugin_id=='Wordpress'){
                            _e('Element Positions and CSS Styles', 'ar-for-wordpress' );
                            if ($disabled!=''){echo ' - '.__('Premium Plans Only', 'ar-for-wordpress');}
                        }elseif ($ar_plugin_id=='Woocommerce'){
                            _e('Element Positions and CSS Styles', 'ar-for-woocommerce' );
                            if ($disabled!=''){echo ' - '.__('Premium Plans Only', 'ar-for-woocommerce');}
                        }
                        ?></h3>
            <?php //CSS Positions
            $ar_css_positions = get_option('ar_css_positions');
            foreach ($ar_css_names as $k => $v){
                ?>
                <div>
                  <div style="width:160px;float:left;"><strong>
                      <?php
                    	    if ($ar_plugin_id=='Wordpress'){
                                _e($k, 'ar-for-wordpress' );
                            }elseif ($ar_plugin_id=='Woocommerce'){
                                _e($k, 'ar-for-woocommerce' );
                            }
                            ?> </strong></div>
                  <div style="float:left;"><select id="ar_css_positions[<?=$k;?>]" name="ar_css_positions[<?=$k;?>]" <?= $disabled;?>>
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
            <div>
              <div style="width:160px;float:left;"><strong>
                  <?php
                  $ar_css = get_option('ar_css');
                  if ($ar_css==''){
                      $ar_css=file_get_contents(esc_url( plugins_url( "assets/css/ar-display-custom.css", __FILE__ ) ));
                  }
                	    if ($ar_plugin_id=='Wordpress'){
                            _e('CSS Styling', 'ar-for-wordpress' );
                        }elseif ($ar_plugin_id=='Woocommerce'){
                            _e('CSS Styling', 'ar-for-woocommerce' );
                        }
                        ?> </strong></div>
              <div style="float:left;"><textarea id="ar_css" name="ar_css" style="width: 450px; height: 200px;" <?= $disabled;?>><?php echo $ar_css; ?></textarea></div>
            </div>
            <br  clear="all">
            <?php 
            if ($ar_plugin_id=='Wordpress'){
                submit_button();
            } ?>
    
    
        </div>
        
        </div>
        <div class="licence_key" id="key" style="float:left;">
        <?php
        if (isset($_REQUEST['tab'])){
            if ($_REQUEST['tab']=='ar_display'){
                echo $woocommerce_featured_image;
            }
        }
        echo $shortcode_examples;
        ?>
        
        <h3><?php
                	    if ($ar_plugin_id=='Wordpress'){
                            _e('Dimensions', 'ar-for-wordpress' );
                        }elseif ($ar_plugin_id=='Woocommerce'){
                            _e('Dimensions', 'ar-for-woocommerce' );
                        }
                        ?></h3> 
        
        <p><?php if ($ar_plugin_id=='Wordpress'){
                            _e('The dimensions show the X, Y, Z, (width, height, depth) directly from the 3D model file. You can turn this off site wide and/or on a per model basis.', 'ar-for-wordpress' );
                        }elseif ($ar_plugin_id=='Woocommerce'){
                            _e('The dimensions show the X, Y, Z, (width, height, depth) directly from the 3D model file. You can turn this off site wide and/or on a per model basis.', 'ar-for-woocommerce' );
                        }
                        ?></p>
        <?php if ($ar_whitelabel!=true){ ?>
        <hr>
        <p class = "further_info"> <?php
                	    if ($ar_plugin_id=='Wordpress'){
                            _e('For further information and assistance using the plugin and converting your models please visit', 'ar-for-wordpress' );
                        }elseif ($ar_plugin_id=='Woocommerce'){
                            _e('For further information and assistance using the plugin and converting your models please visit', 'ar-for-woocommerce' );
                        }
                        ?> <a href = "https://augmentedrealityplugins.com" target = "_blank">https://augmentedrealityplugins.com</a></p>
        <?php } ?>
        </div>
        <?php if ($ar_whitelabel!=true){ ?>
            <div style="float:left;"><a href="https://augmentedrealityplugins.com" target="_blank"><img src="https://augmentedrealityplugins.com/ar/images/ar_wordpress_ad.jpg" style="padding:10px 10px 10px 0px;"><img src="https://augmentedrealityplugins.com/ar/images/ar_woocommerce_ad.jpg" style="padding:10px 10px 10px 0px;"></a></div>
        <?php } 
        wp_enqueue_media();
        ?>
        <br clear="all">
        <script>
            jQuery(document).ready(function($){
            
            var custom_uploader;
            
            $('#ar_wl_file_button, #ar_view_file_button, #ar_qr_file_button, #ar_fullscreen_file_button').click(function(e) {
                var button_clicked = event.target.id;
                var target = button_clicked.substr(0, button_clicked.length -7);
                e.preventDefault();
                //Extend the wp.media object
                custom_uploader = wp.media.frames.file_frame = wp.media({
                    
                    multiple: false
                });
        
                //When a file is selected, grab the URL and set it as the text field's value
                custom_uploader.on('select', function() {
                    var attachments = custom_uploader.state().get('selection').map( 
                       function( attachment ) {
                           attachment.toJSON();
                           return attachment;
                      });
                     $.each(attachments, function( index, attachement ) {
                          
                          var fileurl=attachments[index].attributes.url;
                            var filetype = fileurl.substring(fileurl.length - 4, fileurl.length).toLowerCase();
                            if ((filetype === '.jpg') || (filetype === '.png')){
                                $('#' + target).val(fileurl);  
                                
                            }else{
                                <?php
                	    if ($ar_plugin_id=='Wordpress'){
                           $js_alert = __('Invalid file type. Please choose a JPG or PNG file.', 'ar-for-wordpress' );
                        }elseif ($ar_plugin_id=='Woocommerce'){
                            $js_alert = __('Invalid file type. Please choose a JPG or PNG file.', 'ar-for-woocommerce' );
                        }
                        ?>
                                 alert('<?php echo $js_alert;?>');
                            }
                     });
         
                });
                //Open the uploader dialog
                custom_uploader.open();
            });  
            
            
        });
        </script>
        <?php
    } 
}

/*** QR Code + Logo Generator */
if (!function_exists('ar_qr_code')){
    function ar_qr_code($logo,$data) {
        $data = isset($data) ? $data : 'https://augmentedrealityplugins.com';
        $size = isset($size) ? $size : '250x250';
        $logo = isset($logo) ? $logo : FALSE;
        $QR = imagecreatefrompng('https://chart.googleapis.com/chart?cht=qr&chld=H|1&chs='.$size.'&chl='.urlencode($data));
        if($logo !== FALSE){
        	$logo = imagecreatefromstring(file_get_contents($logo));
        
        	$QR_width = imagesx($QR);
        	$QR_height = imagesy($QR);
        	
        	$logo_width = imagesx($logo);
        	$logo_height = imagesy($logo);
        	
        	// Scale logo to fit in the QR Code
        	$logo_qr_width = $QR_width/3;
        	$scale = $logo_width/$logo_qr_width;
        	$logo_qr_height = $logo_height/$scale;
        	
        	imagecopyresampled($QR, $logo, $QR_width/3, $QR_height/3, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
        }

        ob_start();
        imagepng($QR);
        $imgData=ob_get_clean();
        if (!is_bool($QR)){
            imagedestroy($QR);
        }
        return $imgData;
    }
}

remove_action( 'shutdown', 'wp_ob_end_flush_all', 1 );
add_action( 'shutdown', function() {
   while ( @ob_end_flush() );
} );

//Encode custom CSS code for importing into text field
if (!function_exists('ar_encodeURIComponent')){
    function ar_encodeURIComponent($str) {
        $unescaped = array(
            '%2D'=>'-','%5F'=>'_','%2E'=>'.','%21'=>'!', '%7E'=>'~',
            '%2A'=>'*', '%27'=>"'", '%28'=>'(', '%29'=>')'
        );
        $reserved = array(
            '%3B'=>';','%2C'=>',','%2F'=>'/','%3F'=>'?','%3A'=>':',
            '%40'=>'@','%26'=>'&','%3D'=>'=','%2B'=>'+','%24'=>'$'
        );
        $score = array(
            '%23'=>'#'
        );
        return strtr(rawurlencode($str), array_merge($reserved,$unescaped,$score));
    }
}