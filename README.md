# myuw-badge

![Screenshot showing 'Find it in MyUW' badge in each of the themes. A Madison-red theme including the Madison crest. A black theme with only the bare 'Find it in MyUW' text](badges.png "Badge themes")

A badge with two available themes to help users discover new widgets and content in MyUW.

## Getting Started

Include the component in your page:

```html
<!-- import the module -->
<script type="module" src="https://cdn.my.wisc.edu/@myuw-web-components/myuw-badge@latest/myuw-badge.min.mjs"></script>

<!-- fallback for browsers without ES2015 module support -->
<script nomodule scr="https://cdn.my.wisc.edu/@myuw-web-components/myuw-badge@latest/myuw-badge.min.js"></script>

<myuw-badge
  url=""
  dark-theme
  white-border
>
</myuw-badge>
```

_Note:_ The evergreen "latest" version can be used for convenience, but in production settings it is recommended to use the latest [release version](https://github.com/myuw-web-components/myuw-badge/releases) specifically, and upgrade only after testing!

### Configurable properties via attributes

- **url:** (Required) Set the URL the badge should link to (e.g. The detail page for a MyUW widget)
- **dark-theme:** If this attribute is present, the badge will use the black theme (red by default). The red theme is specific to UW-Madison; the black theme is appropriate for both UW-Madison and other MyUW contexts (e.g. my.wisconsin or my.uwrf)
- **white-border:** If this attribute is present, the badge will have a white border (e.g. when using the badge on a dark background)

## Development and contribution

To run the demo app locally and test the component, run the following commands:

```bash
$ npm install
$ npm start
```


# Wordpress functions 

Enqueue the script on the specific page(s)
```sh
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
```
 Add myuw-badge shortcode with url attribute
```sh
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
```



