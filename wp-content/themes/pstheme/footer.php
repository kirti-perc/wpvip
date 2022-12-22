              
        
<?php /* ?>                        
<section  class="section-padding footer-upper-sec py-5">
        <div class="container">

           <?php         

                              $footer_location = get_theme_mod( 'ps_address' );
                              $footer_email = get_theme_mod( 'ps_email' );
                              $footer_phone = get_theme_mod( 'ps_phone' );
                              $fblink = get_theme_mod( 'ps_social_fb' );
                              $twlink = get_theme_mod( 'ps_social_tw' );
                              $instlink = get_theme_mod( 'ps_social_inst' );
                              $footer_sub_title = get_theme_mod( 'footer_about_text' );

                              ?>
                               <div class="row footer-part">
                                    <div class="col-md-3">
                                      <h4 class="foot-sec-itm-tit"><i aria-hidden="true" class="fas fa-map-marker-alt"></i>Address</h2>
                                      <p class="details"><?php echo $footer_location; ?></p>
                                    </div>                                  
                                     <div class="col-md-3">
                                      <h4 class="foot-sec-itm-tit"><i aria-hidden="true" class="fas fa-phone-alt"></i>Phone</h2>
                                      <p class="details"><a href="tel:<?php echo $footer_phone;?>" ><?php echo $footer_phone; ?></a></p>
                                    </div>
                                       <div class="col-md-3">
                                      <h4 class="foot-sec-itm-tit"><i aria-hidden="true" class="fas fa-envelope"></i>Mail</h2>
                                      <p class="details"><a href="tel:<?php echo $footer_email;?>" ><?php echo $footer_email; ?></a></p>
                                    </div>
                                    <div class="col-md-3">
                                      <div class="footer-logo">
                                          <h4 class="foot-sec-itm-tit">Social</h2>
                                          <ul class="footer-link ul-style p-0 m-0">
                                            <?php if($fblink!=''){ ?>
                                            <li id="facebook"><a href="<?php echo $fblink; ?>" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
                                            <?php } ?>
                                            <?php if($twlink!=''){ ?>
                                            <li id="twitter"><a href="<?php echo $twlink;?>" target="_blank"><i class="fab fa-twitter"></i></a></li>
                                            <?php } ?>
                                            <?php if($instlink!=''){ ?>
                                            <li id="twitter"><a href="<?php echo $instlink?>" target="_blank"><i class="fab fa-instagram"></i></a></li>
                                            <?php } ?>
                                            </ul>
                                      </div>
                                   </div>
                              </div>  

        </div>
</section>
<?php */ ?>
<div class="footer-bottom py-5 text-center">
  <div class="container my-2">    
   <?php $footer_copyright_text = get_theme_mod( 'footer_copyright_text' ); ?>
          <?php echo $footer_copyright_text; ?>
  </div>
</div>
</div>
    <?php wp_footer(); ?>
    </body>
</html>
