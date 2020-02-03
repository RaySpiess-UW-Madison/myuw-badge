<?php

/*
  Enqueue scripts and add shortcode for webcomponent

  Webcomponent: <myuw-badge url="" {dark-theme} {white-border} > 
  Shortcode:   <myuw-badge url="" theme="{dark-theme} border={white-border}
*/ 

// Enqueue the script on the specific page 
function myuw_badge_component_scripts($page) {
   global $post;

    // register and enqueue scripts 
    if ( WP_DEBUG ) {
      wp_register_script('uw-webcomponents-myuw-badge',
      get_stylesheet_directory_uri().'/assets/webcomponents/myuw-badge/js/myuw-badge.js',
      array( 'jquery' ),  null, true); //footer true
    }
    else {
      wp_register_script('uw-webcomponents-myuw-badge',
      get_stylesheet_directory_uri().'/assets/webcomponents/myuw-badge/js/myuw-badge.min.js',
      array( 'jquery' ),  null, true); //footer true
    }

    // use the following to limit the number of pages where the  scripts are loaded. 

    if( is_singular()) {
   // if( is_singular( $post_types = 'service')) {
  //  if( is_page( array( 'sandbox','web-hosting', 'scheduling-group-m'))) {
  //  if( has_shortcode( $post->post_content, 'myuw-badge') ) {
     wp_enqueue_script( 'uw-webcomponents-myuw-badge');
 }
}
 add_action('wp_enqueue_scripts', 'myuw_badge_component_scripts', 999);

 // Add myuw-badge shortcode with url attribute
function myuw_badge_shortcode($atts) {

   $url = "https://my.wisc.edu";
   $theme = "";
   $border = "";

   if ($atts["url"] != "") {
      shortcode_atts( array(
        'url',
        'theme', 
        'border'
      ), $atts);
   $url = $atts['url'];
   $theme = $atts['theme'];
   $border = $atts['border'];
  }
  return '<myuw-badge url="'. $url .'" '.$theme.' '.$border.'></myuw-badge>';
}
add_shortcode('myuw-badge', 'myuw_badge_shortcode');

