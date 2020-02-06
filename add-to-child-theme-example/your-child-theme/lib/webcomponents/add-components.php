<?php
function myuw_badge_component_scripts($page) {
    global $post;

    if ( WP_DEBUG ) {
        wp_register_script( 'module', 'https://cdn.my.wisc.edu/@myuw-web-components/myuw-badge@1.1.0/myuw-badge.mjs', null, null, true );
    }
    else {
        wp_register_script( 'module', 'https://cdn.my.wisc.edu/@myuw-web-components/myuw-badge@1.1.0/myuw-badge.min.mjs', null, null, true );
    }

    // try to use one of the following to limit pages where script is added
    //   if( is_singular()) {
    //   if( is_singular( $post_types = 'service')) {  
    //   if( is_page( array( 'sandbox'))) { // by name
    //   if( has_shortcode( $post->post_content, 'myuw-badge') ) {  
    
    if( is_singular()) {
       wp_enqueue_script('module');
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
         'theme'=>'', 
         'border'=>''
       ), $atts);
    $url = $atts['url'];
    $theme = $atts['theme'];
    $border = $atts['border'];
   }
   return '<myuw-badge url="'. $url .'" '.$theme.' '.$border.'></myuw-badge>';
 }
 add_shortcode('myuw-badge', 'myuw_badge_shortcode');