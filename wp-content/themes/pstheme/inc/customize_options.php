<?php
/* add custom option for */
add_action( 'customize_register', 'psth_customizer_settings' );
function psth_customizer_settings( $wp_customize ) {
	$wp_customize->add_section( 'ps_settings' , array(
		'title'      => 'Site Options',
		'priority'   => 40,
	) );

	$wp_customize->add_setting( 'ps_phone' , array(
		'default'     => 'Phone'
	) );

	$wp_customize->add_control( 'ps_phone', array(
		'label'        => 'Phone',
		'section'    => 'ps_settings',
		'type'	 => 'text',
	) );

	$wp_customize->add_setting( 'ps_email' , array(
		'default'     => 'Email'
	) );

	$wp_customize->add_control( 'ps_email', array(
		'label'        => 'Email',
		'section'    => 'ps_settings',
		'type'	 => 'text',
	) );	

	$wp_customize->add_setting( 'ps_address' , array(
		'default'     => ''
	) );

	$wp_customize->add_control( 'ps_address', array(
		'label'        => 'Address',
		'section'    => 'ps_settings',
		'type'	 => 'textarea',
	) );
		
	$wp_customize->add_section( 'ps_social' , array(
		'title'      => 'Social Links',
		'priority'   => 40,
	) );

	$wp_customize->add_setting( 'ps_social_fb' , array(
		'default'     => 'Facebook'
	) );

	$wp_customize->add_control( 'ps_social_fb', array(
		'label'        => 'Facebook',
		'section'    => 'ps_social',
		'type'	 => 'text',
	) );

	$wp_customize->add_setting( 'ps_social_tw' , array(
		'default'     => 'Twitter'
	) );

	$wp_customize->add_control( 'ps_social_tw', array(
		'label'        => 'Twitter',
		'section'    => 'ps_social',
		'type'	 => 'text',
	) );

	$wp_customize->add_setting( 'ps_social_inst' , array(
		'default'     => 'Instagram'
	) );

	$wp_customize->add_control( 'ps_social_inst', array(
		'label'        => 'Instagram',
		'section'    => 'ps_social',
		'type'	 => 'text',
	) );

	$wp_customize->add_setting( 'ps_social_pt' , array(
		'default'     => 'Pintrest'
	) );

	$wp_customize->add_control( 'ps_social_pt', array(
		'label'        => 'Pintrest',
		'section'    => 'ps_social',
		'type'	 => 'text',
	) );
	
	
	$wp_customize->add_setting( 'footer_logo_img' , array(
		'default'     => ''
	) );	

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'footer_logo_img', array(
		'label' => 'Edit Footer Logo',
		'settings'  => 'footer_logo_img',
		'section'   => 'ps_settings'
	) ));

	$wp_customize->add_setting( 'footer_about_text' , array(
		'default'     => ''
	) );	

	$wp_customize->add_control( 'footer_about_text', array(
		'label'        => 'About',
		'section'    => 'ps_settings',
		'type'	 => 'textarea',
	) );	

	$wp_customize->add_setting( 'footer_copyright_text' , array(
		'default'     => ''
	) );	

	$wp_customize->add_control( 'footer_copyright_text', array(
		'label'        => 'Copyright',
		'section'    => 'ps_settings',
		'type'	 => 'textarea',
	) );



}
