<?php
if ( ! function_exists( 'psth_enqueue_script_func_callback' ) ) {
	function psth_enqueue_script_func_callback() {
	   
        wp_register_style(
	      'main-style',
	      get_stylesheet_directory_uri() . '/style.css'
	    );
	    wp_enqueue_style( 'main-style');
     
     
       wp_dequeue_style( 'bootstrap-style' );
 
	   wp_enqueue_style( 'bootstrape', get_stylesheet_directory_URI() . '/assets/vendor/bootstrap/css/bootstrap.min.css', array(), 1.1);
	   wp_enqueue_style( 'fontawesome', get_stylesheet_directory_URI() . '/assets/vendor/fontawesome/css/all.min.css', array(), 1.1);
	    
     wp_enqueue_style( 'owlcarousel-style', get_stylesheet_directory_URI() . '/assets/vendor/OwlCarousel/css/owl.carousel.min.css', array(), 1.1);
     wp_enqueue_style( 'owlcarousel-style-theme', get_stylesheet_directory_URI() . '/assets/vendor/OwlCarousel/css/owl.theme.default.min.css', array(), 1.1);


      //wp_enqueue_style( 'elementor-frontend-legacy');
      wp_enqueue_style( 'elementor-frontend');

      wp_enqueue_style( 'theme-style', get_stylesheet_directory_URI() . '/assets/css/style.css', array('elementor-frontend'), filemtime(get_stylesheet_directory() .'/assets/css/style.css'), 'all' );
	    wp_enqueue_style( 'responsive', get_stylesheet_directory_URI() . '/assets/css/responsive.css', array(), filemtime(get_stylesheet_directory() .'/assets/css/responsive.css'), 'all' );

	    
	    //wp_enqueue_script( 'slim', get_stylesheet_directory_URI() . '/assets/vendor/jquery/jquery-3.4.1.slim.min.js', array( 'jquery'), 1.1, true ); 
	    //wp_enqueue_script( 'popper', get_stylesheet_directory_URI() . '/assets/vendor/jquery/popper.min.js', array( 'jquery'), 1.1, true ); 	    

     

      wp_dequeue_script( 'bootstrap-js' ); 

      wp_enqueue_script( 'bootstrape-js', get_stylesheet_directory_URI() . '/assets/vendor/bootstrap/js/bootstrap.bundle.min.js', array( 'jquery'), 1.1, true ); 

      wp_enqueue_script( 'owlcarousel-js', get_stylesheet_directory_URI() . '/assets/vendor/OwlCarousel/js/owl.carousel.min.js', array( 'jquery'), 1.1, true ); 

	    wp_enqueue_script( 'script-custom', get_stylesheet_directory_uri() . '/assets/js/custom.js', array('jquery'), filemtime(get_stylesheet_directory() .'/assets/js/custom.js'), true );

	 }
}
add_action( 'wp_enqueue_scripts', 'psth_enqueue_script_func_callback' );

/*Write here your own functions */

require_once get_stylesheet_directory() . '/inc/customize_options.php';
require_once get_stylesheet_directory() . '/inc/shortcodes.php';


function psth_theme_support() {

    // Add default posts and comments RSS feed links to head.
    add_theme_support( 'automatic-feed-links' );    
   
    add_theme_support( 'post-thumbnails' );

    // Set post thumbnail size.
    set_post_thumbnail_size( 1200, 9999 );

    // Add custom image size used in Cover Template.
    add_image_size( 'lwe-fullscreen', 1980, 9999 );

   
    add_theme_support( 'title-tag' );

    add_theme_support(
        'html5',
        array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'script',
            'style',
        )
    );

    load_theme_textdomain( 'lwe' );

    // Add support for full and wide align images.
    add_theme_support( 'align-wide' );

    // Add support for responsive embeds.
    add_theme_support( 'responsive-embeds' );

    
    // Add theme support for selective refresh for widgets.
    add_theme_support( 'customize-selective-refresh-widgets' );

        
    add_theme_support(
            'custom-logo'
            /*array(
                'height'      => 190,
                'width'       => 190,
                'flex-width'  => false,
                'flex-height' => false,
            )*/
        );


}

add_action( 'after_setup_theme', 'psth_theme_support' );

function psth_sidebar_registration() {

    // Arguments used in all register_sidebar() calls.
    $shared_args = array(
        'before_title'  => '<h2 class="widget-title subheading heading-size-3">',
        'after_title'   => '</h2>',
        'before_widget' => '<div class="widget %2$s"><div class="widget-content">',
        'after_widget'  => '</div></div>',
    );

    // Footer #1.
    register_sidebar(
        array_merge(
            $shared_args,
            array(
                'name'        => __( 'Footer About Us', 'pstheme' ),
                'id'          => 'sidebar-1',
                'description' => __( 'description about Company', 'pstheme' )
            )
        )
    );    

}

add_action( 'widgets_init', 'psth_sidebar_registration' );

function psth_register_menus() {
  register_nav_menus(
    array(
      'main-menu' => __( 'Main Menu', 'pstheme' ),
      'top-right-menu' => __( 'Top Right Menu', 'pstheme' )      
    )
  );
}
add_action( 'init', 'psth_register_menus' );


function theme_main_menu() {  

  $args= array();  
  $menu_items = wp_get_nav_menu_items( 'main menu', $args ); 

  $menu_list = '<ul class="navbar-nav ml-auto nav-link">' ."\n";

  $count = 0;
  $submenu = false;

  if(!empty($menu_items)) {

  foreach( $menu_items as $menu_item ) {

  $link = $menu_item->url;
  $title = $menu_item->title;
  $current = ( $menu_item->object_id == get_queried_object_id() ) ? 'current' : '';

        if ( !$menu_item->menu_item_parent ) {
        $parent_id = $menu_item->ID;

                
                $children = get_posts(array('post_type' => 'nav_menu_item', 'nopaging' => true, 'numberposts' => 1, 'meta_key' => '_menu_item_menu_item_parent', 'meta_value' => $parent_id));


                if (!empty($children)) {                    

                    $menu_list .= '<li class="nav-item dropdown '.$current.'">' ."\n";
                    $menu_list .= '<a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">'.$title.'</a>' ."\n";       
                }
                else{                  

                  $menu_list .= '<li class="nav-item '.$current.'">' ."\n";
                   $menu_list .= '<a href="'.$link.'" class="nav-link">'.$title.'</a>' ."\n";


                }



        }

        

        if ( $parent_id == $menu_item->menu_item_parent ) {
             

              if ( !$submenu ) {

              $submenu = true;
              $menu_list .= '<ul class="dropdown-menu">' ."\n";
              }

              $menu_list .= '<li class="'.$current.'">' ."\n";
              $menu_list .= '<a href="'.$link.'" class="dropdown-item">'.$title.'</a>' ."\n";
              $menu_list .= '</li>' ."\n";


              if ( $menu_items[ $count + 1 ]->menu_item_parent != $parent_id && $submenu ){
              $menu_list .= '</ul>' ."\n";
              $submenu = false;
              }

        }

        if ( isset($menu_items[ $count + 1 ]) && $menu_items[ $count + 1 ]->menu_item_parent != $parent_id ) { 
        $menu_list .= '</li>' ."\n";      
        $submenu = false;
        }

  $count++;
  }

  }

  $menu_list .= '</ul>' ."\n";

  return $menu_list;

                             
}

function pq_custom_pagination($query) {

    global $wp_query;
    $big = 999999999; // need an unlikely integer
    $pages = paginate_links( array(
        'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
        'format' => '?paged=%#%',
        'add_args' => $_POST,
        'show_all' => true,
        'current' => max( 1, get_query_var('paged') ),
        'total' => $query->max_num_pages,        
        'type'  => 'array',
        'prev_next'   => true,
        'prev_text'    => __( '«', 'pstheme' ),
        'next_text'    => __( '»', 'pstheme'),
    ) );
    $output = '';

    if ( is_array( $pages ) ) {
        $paged = ( get_query_var('paged') == 0 ) ? 1 : get_query_var( 'paged' );

        $output .=  '<ul class="pagination pagination-sm justify-content-end custom-pagination">';
        foreach ( $pages as $page ) {
            $output .= "<li class='page-item'>$page</li>";
        }
        $output .= '</ul>';

        // Create an instance of DOMDocument 
        $dom = new \DOMDocument();

        // Populate $dom with $output, making sure to handle UTF-8, otherwise
        // problems will occur with UTF-8 characters.
        $dom->loadHTML( mb_convert_encoding( $output, 'HTML-ENTITIES', 'UTF-8' ) );

        // Create an instance of DOMXpath and all elements with the class 'page-numbers' 
        $xpath = new \DOMXpath( $dom );

        // http://stackoverflow.com/a/26126336/3059883
        $page_numbers = $xpath->query( "//*[contains(concat(' ', normalize-space(@class), ' '), ' page-numbers ')]" );

        // Iterate over the $page_numbers node...
        foreach ( $page_numbers as $page_numbers_item ) {

            // Add class="mynewclass" to the <li> when its child contains the current item.
            $page_numbers_item_classes = explode( ' ', $page_numbers_item->attributes->item(0)->value );


            /* if ( in_array( 'current', $page_numbers_item_classes ) ) {          
                $list_item_attr_class = $dom->createAttribute( 'class' );
                $list_item_attr_class->value = 'mynewclass';
                $page_numbers_item->parentNode->appendChild( $list_item_attr_class );
            }*/

            // Replace the class 'current' with 'active'
            $page_numbers_item->attributes->item(0)->value = str_replace( 
                            'current',
                            'active',
                            $page_numbers_item->attributes->item(0)->value );

            // Replace the class 'page-numbers' with 'page-link'
            $page_numbers_item->attributes->item(0)->value = str_replace( 
                            'page-numbers',
                            'page-link',
                            $page_numbers_item->attributes->item(0)->value );
        }

        // Save the updated HTML and output it.
        $output = $dom->saveHTML();
    }

    return $output;
}



function psth_create_posttype_testimonials() {
 
    register_post_type( 'testimonials',
    // CPT Options
        array(
            'labels' => array(
                'name' => __( 'Testimonials', 'pstheme' ),
                'singular_name' => __( 'Testimonial', 'pstheme' )
            ),
            'public' => true,
            'publicly_queryable' => true,
            'has_archive' => false,
            'rewrite' => array('slug' => 'testimonials'),
            'show_in_rest' => true,
            'supports'   => array( 'title', 'editor','thumbnail', 'custom-fields' ),
 
        )
    );
}
add_action( 'init', 'psth_create_posttype_testimonials' );


function psth_create_posttype_home_services() {
 
    register_post_type( 'services',
    // CPT Options
        array(
            'labels' => array(
                'name' => __( 'Services', 'pstheme' ),
                'singular_name' => __( 'Service', 'pstheme' )
            ),
            'public' => true,
            'publicly_queryable' => true,
            'has_archive' => false,
            'rewrite' => array('slug' => 'services'),
            'show_in_rest' => true,
            'supports'   => array( 'title', 'editor','thumbnail' ),
 
        )
    );
}
add_action( 'init', 'psth_create_posttype_home_services' );

