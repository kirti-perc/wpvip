<?php 


add_shortcode( 'post_carousel', 'psth_post_carousel_func' );
function psth_post_carousel_func( $atts ) {
    
     $defaults = array(
        'numberposts'      => -1,
        'category'         => 0,
        'orderby'          => 'date',
        'order'            => 'DESC',
        'include'          => array(),
        'exclude'          => array(),
        'meta_key'         => '',
        'meta_value'       => '',
        'post_type'        => 'post',
        'suppress_filters' => true,
    );


    $atts = shortcode_atts( $defaults, $atts, 'post_carousel' );

    $latest_posts = get_posts( $atts );      
    $post_carousel_html = '';

    $post_carousel_html .= '<div class="owl-carousel owl-theme " id="post_carousel">';

    if ( $latest_posts ) {
        foreach ( $latest_posts as $post ) : 

            setup_postdata( $post ); 
            $title = $post->post_title;
            $link = get_permalink($post->ID); 
            $excerpt = $post->post_excerpt;
            $day = date('d',strtotime($post->post_date));
            $month = date('M',strtotime($post->post_date));
            $year = date('Y',strtotime($post->post_date));
            $post_carousel_html .= '<div class="item"><div class="row post-wraper">';
            $post_carousel_html .= '<div class="caro-post-date-sec col-md-3 text-center">';
            $post_carousel_html .= '<span class="caro-post-day">'.$day.'</span>';
            $post_carousel_html .= '<span class="caro-post-month">'.$month.'</span>';
            $post_carousel_html .= '<span class="caro-post-year">'.$year.'</span>';
            $post_carousel_html .= '</div>';
            $post_carousel_html .= '<div class="caro-post-content-sec col-md-9">';
            $post_carousel_html .= '<a class="post-title" href="'.$link.'">'.$title.'</a>';
            $post_carousel_html .= '<p class="post-description">'.$excerpt.'</p>';
            $post_carousel_html .= '</div>';
            $post_carousel_html .= '</div></div>';
        
        endforeach;
        wp_reset_postdata();
    }
  
    $post_carousel_html .= '</div>';
    return $post_carousel_html;    
    
}


add_shortcode( 'testimonials_carousel', 'psth_testimonials_carousel_func' );
function psth_testimonials_carousel_func( $atts ) { 

     $defaults = array(
        'numberposts'      => -1,
        'category'         => 0,
        'orderby'          => 'date',
        'order'            => 'DESC',
        'include'          => array(),
        'exclude'          => array(),
        'meta_key'         => '',
        'meta_value'       => '',
        'post_type'        => 'testimonials',
        'suppress_filters' => true,
    );


    $atts = shortcode_atts( $defaults, $atts, 'testimonials_carousel' );

    //var_dump($atts);

    $latest_posts = get_posts( $atts );      
    $post_carousel_html = '';

    $post_carousel_html .= '<div class="owl-carousel owl-theme " id="testimonials_carousel">';

    if ( $latest_posts ) {
        foreach ( $latest_posts as $post ) : 

            setup_postdata( $post ); 
            $title = $post->post_title;
            $link = get_permalink($post->ID); 
            $company = get_post_meta($post->ID,'Company',true);
            $star_rating = get_post_meta($post->ID,'Star Rating',true);
            $content = $post->post_content;
            $post_image = get_the_post_thumbnail_url($post->ID,'full');
            $post_carousel_html .= '<div class="item" data-dot="'.$post_image.'"><div class="row rel-post-wraper p-3">';
            $post_carousel_html .= '<div class="rel-caro-post-content-sec">';
            $post_carousel_html .= '<div class="col-md-3">';
            $post_carousel_html .= '<div class="rel-caro-post-img-sec">';
            
            if($post_image != '') {

                $post_carousel_html .= '<img src="'.$post_image.'" class="blog-list-image"/>';

            }

            $post_carousel_html .= '</div>';    
            $post_carousel_html .= '</div>'; 
            $post_carousel_html .= '<div class="col-md-3">';                    
            $post_carousel_html .= '<h3>'.$title.'</h3>';
            $post_carousel_html .= '<span>'.$company.'</span>';
            $post_carousel_html .= '</div>'; 
            $post_carousel_html .= '<div class="rel-post-description">'.$content.'</div>';
            $post_carousel_html .= '<div class="star-rat">';
            
            $star_rat = '';

            for($i=1;$i<=$star_rating;$i++){

                $star_rat .= '<i class="fa fa-star" aria-hidden="true"></i>';
                
            }

            $post_carousel_html .= $star_rat;            
            $post_carousel_html .= '</div>';           
            $post_carousel_html .= '</div>';
            $post_carousel_html .= '</div></div>';
        
        endforeach;
        wp_reset_postdata();
    }
  
    $post_carousel_html .= '</div>';
    return $post_carousel_html;    
    
}

add_shortcode( 'services_carousel', 'psth_services_carousel_func' );
function psth_services_carousel_func( $atts ) {

     $defaults = array(
        'numberposts'      => -1,
        'category'         => 0,
        'orderby'          => 'date',
        'order'            => 'DESC',
        'include'          => array(),
        'exclude'          => array(),
        'meta_key'         => '',
        'meta_value'       => '',
        'post_type'        => 'services',
        'suppress_filters' => true,
    );


    $atts = shortcode_atts( $defaults, $atts, 'services_carousel' );

    //var_dump($atts);

    $latest_posts = get_posts( $atts );      
    $post_carousel_html = '';

    $post_carousel_html .= '<div class="owl-carousel owl-theme " id="services_carousel">';

    if ( $latest_posts ) {
        foreach ( $latest_posts as $post ) : 

    

            setup_postdata( $post ); 
            $title = $post->post_title;
            $content = $post->post_content;
            $post_image = get_the_post_thumbnail_url($post->ID,'medium');
            $post_carousel_html .= '<div class="item"><div class="row rel-post-wraper p-3">';
            $post_carousel_html .= '<div class="rel-caro-post-content-sec">';            
            $post_carousel_html .= '<div class="rel-caro-post-img-sec">';
            
            if($post_image != '') {

                $post_carousel_html .= '<img src="'.$post_image.'" class="blog-list-image"/>';

            }

            $post_carousel_html .= '</div>';    
           // $post_carousel_html .= '</div>'; 
            //$post_carousel_html .= '<div class="col-md-3">';                    
            $post_carousel_html .= '<h3>'.$title.'</h3>';            
           
            $post_carousel_html .= '<div class="rel-post-description">'.$content.'</div>';
            $post_carousel_html .= '</div>'; 
            $post_carousel_html .= '</div></div>';
        
        endforeach;
        wp_reset_postdata();
    }
  
    $post_carousel_html .= '</div>';
    return $post_carousel_html;    
    
}

add_shortcode( 'related_post_carousel', 'psth_related_post_carousel_func' );
function psth_related_post_carousel_func( $atts ) {

     $defaults = array(
        'numberposts'      => -1,
        'category'         => 0,
        'orderby'          => 'date',
        'order'            => 'DESC',
        'include'          => array(),
        'exclude'          => array(),
        'meta_key'         => '',
        'meta_value'       => '',
        'post_type'        => 'post',
        'suppress_filters' => true,
    );


    $atts = shortcode_atts( $defaults, $atts, 'related_post_carousel' );

    //var_dump($atts);

    $latest_posts = get_posts( $atts );      
    $post_carousel_html = '';

    $post_carousel_html .= '<div class="owl-carousel owl-theme " id="related_post_carousel">';

    if ( $latest_posts ) {
        foreach ( $latest_posts as $post ) : 

            setup_postdata( $post ); 
            $title = $post->post_title;
            $link = get_permalink($post->ID); 
            $excerpt = $post->post_excerpt;
            $post_image = get_the_post_thumbnail_url($post->ID,'medium');
            $post_carousel_html .= '<div class="item"><div class="row rel-post-wraper p-3">';
            $post_carousel_html .= '<div class="rel-caro-post-content-sec col-md-12">';
            $post_carousel_html .= '<div class="rel-caro-post-img-sec">';
            
            if($post_image != '') {

                $post_carousel_html .= '<a href="'.$link.'"><img src="'.$post_image.'" class="blog-list-image"/></a>';

            }

            $post_carousel_html .= '</div>';                     
            $post_carousel_html .= '<a class="rel-post-title" href="'.$link.'">'.$title.'</a>';
            $post_carousel_html .= '<p class="rel-post-description">'.$excerpt.'</p>';
            $post_carousel_html .= '</div>';
            $post_carousel_html .= '</div></div>';
        
        endforeach;
        wp_reset_postdata();
    }
  
    $post_carousel_html .= '</div>';
    return $post_carousel_html;   
}