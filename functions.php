<?php
	add_theme_support( 'post-thumbnails' );
	//add support for custom logo
	add_theme_support("custom-logo");

	//add cors support
	function add_cors_http_header() {
		header("Access-Control-Allow-Origin: *");
	}
	add_action("init","add_cors_http_header");

	// enqueue or Stylesheets - Wordpress not the React Frontend:
	function enqueue_parent_and_custom_styles() {
        // parent theme style
		wp_enqueue_style("parent-style" ,get_template_directory_uri() . '/style.css');

		// custom styles:
		wp_enqueue_style("child-style" ,get_template_directory_uri() . '/custom.css' , array("parent-style"));
    }
	add_action("wp_enqueue_scripts" , "enqueue_parent_and_custom_styles");

	//declare the function
	function custom_excerpt_length($length){
		return 20;
	}
	//call the function within the correct WP hook
	add_filter('excerpt_length', "custom_excerpt_length", 999 );

	// Customiser Settings
	// Use the WordPress Customization API to register these customizer settings --------
	function custom_theme_customize_register( $wp_customize ) {

	// ******** BODY BG COLOUR *********
	// Register and define customizer settings here
	$wp_customize->add_setting('background_color', array(
	  'default' => '#FFFFFF',
	  'transport' => 'postMessage',
	));
	
	// Add a control for the background color
	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'background_color', array(
	  'label' => __('Background Color', 'custom-theme'),
	  'section' => 'colors',
	)));


	// ****** NAVBAR BG COLOUR **********

	$wp_customize->add_setting('navbar_color', array(
		'default' => '#E6E6FA', // Default navbar color
		'transport' => 'postMessage',
	));

	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'navbar_color', array(
		'label' => __('Navbar Color', 'custom-theme'),
		'section' => 'colors',
	)));

	// ******* MOBILE MENU BG COLOUR ********* 
	$wp_customize->add_setting('mobile_menu_color', array(
		'default' => '#E6E6FA', // Default navbar color
		'transport' => 'postMessage',
	));

	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'mobile_menu_color', array(
		'label' => __('Mobile Menu Colour', 'custom-theme'),
		'section' => 'colors',
	)));

	// ******* Button COLOUR ********* 
	$wp_customize->add_setting('button_colour', array(
		'default' => '#89CFF0', // Default navbar color
		'transport' => 'postMessage',
	));

	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'button_colour', array(
		'label' => __('Button Background Colour ', 'custom-theme'),
		'section' => 'colors',
	)));

	}
	add_action( 'customize_register', 'custom_theme_customize_register' );


	// Custom REST API endpoint to retrieve customizer settings
	function get_customizer_settings() {
	$settings = array(
		'backgroundColor' => get_theme_mod('background_color', '#ffffff'),
		'navbarColor' => get_theme_mod('navbar_color', '#E6E6FA'),
		'mobileMenu' => get_theme_mod('mobile_menu_color', '#E6E6FA'),
		'buttonColour' => get_theme_mod('button_colour', '#89CFF0'),
	);

	return rest_ensure_response($settings);
	}

	add_action('rest_api_init', function () {
	register_rest_route('custom-theme/v1', '/customizer-settings', array(
		'methods' => 'GET',
		'callback' => 'get_customizer_settings',
	));
	});

	// ********* GET NAV LOGO SET IN ADMIN DASHBOARD ************
	function get_nav_logo() {
	$custom_logo_id = get_theme_mod('custom_logo');
	$logo = wp_get_attachment_image_src($custom_logo_id, 'full');
	
	return $logo;
	}

	add_action('rest_api_init', function () {
		register_rest_route('custom/v1', 'nav-logo', array(
			'methods' => 'GET',
			'callback' => 'get_nav_logo',
		));
	});
	
?>
