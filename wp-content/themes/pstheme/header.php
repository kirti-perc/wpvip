<!doctype html>
<html <?php language_attributes(); ?>>
   <head>
        <!-- Required meta tags -->
        <meta charset="<?php bloginfo( 'charset' ); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">      
        <?php wp_head(); ?>        
    </head>    
    <body <?php body_class(); ?>>
        <div class="content-wrapper"> 

            <div class="header-top-bar py-3">
              <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-9"> 
                      <div class="header-left-info">
                           <ul class="head-left-info-list ul-style p-0 m-0 text-center text-lg-left">
                            <?php 
                    $ps_phone = get_theme_mod( 'ps_phone' );
                    $ps_email = get_theme_mod( 'ps_email' );
                    $ps_address = get_theme_mod( 'ps_address' );
                   
                    $fbLink = get_theme_mod( 'ps_social_fb' );
                    $twLink = get_theme_mod( 'ps_social_tw' );
                    $igLink = get_theme_mod( 'ps_social_inst' );
                    $ptLink = get_theme_mod( 'ps_social_pt' );
            ?>
                              
                      <?php if($ps_phone!=''){ ?>
                      <li><a href="tel:<?php echo $ps_phone;?>" ><i class="fa fa-phone-alt mr-2" aria-hidden="true"></i><?php echo $ps_phone;?></a></li>
                      <?php } ?>
                      <?php if($ps_email!=''){ ?>
                      <li><a href="mailto:<?php echo $ps_email;?>" ><i class="fas fa-envelope mr-2" aria-hidden="true"></i><?php echo $ps_email;?></a></li>
                      <?php } ?> 
                      </ul>  
                      </div> 
                    </div>
                     <div class="col-md-3 text-center text-lg-right">  
                         <div class="header-soc-icons">
                              <ul class="head-soc-link p-0 ul-style m-0">
                              <?php if($twLink!=''){ ?>
                              <li id="twitter"><a href="<?php echo $twLink;?>" target="_blank"><i class="fab fa-twitter"></i></a></li>
                              <?php } ?>
                              <?php if($igLink!=''){ ?>
                              <li id="instagram"><a href="<?php echo $igLink?>" target="_blank"><i class="fab fa-instagram"></i></a></li>
                              <?php } ?> 
                              <?php if($fbLink!=''){ ?>
                              <li id="facebook"><a href="<?php echo $fbLink; ?>" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
                              <?php } ?>
                              </ul> 
                         </div> 
                    </div>
                </div>
              </div>
            
            </div>
            <nav class="navbar navbar-expand-lg navbar-light px-lg-5 py-3">
                <div class="container">                    
                    
                    <a class="navbar-brand" href="<?php echo site_url(); ?>">
                    <?php
                      $custom_logo_id = get_theme_mod( 'custom_logo' );
                    $logo = wp_get_attachment_image_src( $custom_logo_id , 'full' );
                    if ( has_custom_logo() ) {
                    ?>
                    <img src="<?php echo esc_url( $logo[0] ); ?>" alt="" class="logo">
                    <?php
                    } else {
                      echo '<h1>'. get_bloginfo( 'name' ) .'</h1>';
                    }
                    ?>
                    </a>


                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                      </button>                    
                    <div class="navbar-collapse order-4 order-lg-3 collapse" id="navbarSupportedContent">
                      
                      <?php echo theme_main_menu(); ?> 
                        
                    </div>


                </div>
            </nav>
            