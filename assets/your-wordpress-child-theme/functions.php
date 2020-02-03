<?php // Opening PHP tag of the child theme

// add myuw-badge webcomponent - WIP
include( get_stylesheet_directory() . '/assets/webcomponents/myuw-badge/myuw-badge-component.php');
//

// add acf fields to api
include( get_stylesheet_directory() . '/lib/acf-to-api.php');
//

//* Enqueue child theme style sheet

function uw_child_enqueue_styles() {
    $parent_style = 'uwmadison-style';
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );
}
 add_action( 'wp_enqueue_scripts', 'uw_child_enqueue_styles' );

//* Enqueue fonts
add_action( 'wp_enqueue_scripts', 'load_fonts' );
function load_fonts() {
   //  wp_enqueue_style( 'google-fonts', '//fonts.googleapis.com/css?family=Lato:400,400i,700,700i,900,900i|Source+Sans+Pro:400,400i,600,600i,700,700i,900,900i|Heebo:400,500,700,800,900', array(), 'CHILD_THEME_VERSION' );
   //  wp_enqueue_script( 'google-fonts', get_stylesheet_directory_uri().'/assets/css/FontFace.css');

    wp_enqueue_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css',true );
}
function enq_material_styles() {
  wp_enqueue_style( 'material-icons', '//fonts.googleapis.com/icon?family=Material+Icons',true );
}
add_action( 'wp_enqueue_scripts', 'enq_material_styles' );

function load_custom_wp_admin_style() {
  wp_enqueue_style( 'material-icons', '//fonts.googleapis.com/icon?family=Material+Icons',true );
}
add_action( 'admin_enqueue_scripts', 'load_custom_wp_admin_style' );
/*
function DataTables(){
    wp_enqueue_script( 'datatables', '//cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js', array( 'jquery' ) );
    wp_enqueue_style( 'datatables-style', '//cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css' );
}
add_action( 'wp_enqueue_scripts', 'DataTables' );
*/
//* Remove 'Category: ' from archive pages
add_filter( 'get_the_archive_title', 'remove_category_word_in_archive_title' );

function remove_category_word_in_archive_title( $title ) {
    if ( is_category() ) $title = str_replace( 'Category: ', '', $title );
    return $title;
}

$uwmadison_child_includes = array(
	'lib/acf-helper.php'
);

foreach ($uwmadison_child_includes as $file) {
	if (!$filepath = locate_template($file)) {
		trigger_error(sprintf(__('Error locating %s for inclusion', 'uw-theme'), $file), E_USER_ERROR);
	}
	require_once $filepath;
}
unset($file, $filepath);

include( get_stylesheet_directory() . '/lib/action-hooks/action-hooks.php');
include( get_stylesheet_directory() . '/lib/shortcodes/shortcodes.php');
//include( get_stylesheet_directory() . '/lib/custom.php');

// Register scripts and stylesheets
require_once(get_stylesheet_directory() .'/assets/functions/enqueue-scripts.php');

// Child menus
//require_once(get_stylesheet_directory() .'/assets/functions/child-menus.php');
// require_once(get_stylesheet_directory() .'/assets/functions/topbar-walker-nav-menu.php');

// add tag support to pages
function tags_support_all() {
	register_taxonomy_for_object_type('post_tag', 'page');
}

// ensure all tags are included in queries
function tags_support_query($wp_query) {
	if ($wp_query->get('tag')) $wp_query->set('post_type', 'any');
}

// tag hooks
add_action('init', 'tags_support_all');
add_action('pre_get_posts', 'tags_support_query');

//*******************************************************************
//Move the WordPress Yoast SEO box to the bottom of the edit screen
//*******************************************************************
add_filter( 'wpseo_metabox_prio', function() { return 'low';});
//*******************************************************************
//Template function to consistently formate phone numbers
//*******************************************************************
function format_phone($phone){
    $phone = preg_replace("/[^0-9]/", "", $phone);
    if(strlen($phone) == 7)
        return preg_replace("/([0-9]{3})([0-9]{4})/", "$1-$2", $phone);
    elseif(strlen($phone) == 10)
        return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "($1) $2-$3", $phone);
    else
        return $phone;
}
//*******************************************************************
// Expose CPTs to REST API **/
// consider moving to parent theme or a plugin that gives the user control over whether a given post type is exposed
//credit to Rachel Baker: https://github.com/WP-API/WP-API/issues/1299
//*******************************************************************
function wpsd_add_cpts_args() {
    global $wp_post_types;
    //services
    $wp_post_types['service']->show_in_rest = true;
    $wp_post_types['service']->rest_base = 'services';
    $wp_post_types['service']->rest_controller_class = 'WP_REST_Posts_Controller';
    //document
    $wp_post_types['uw_documents']->show_in_rest = true;
    $wp_post_types['uw_documents']->rest_base = 'documents';
    $wp_post_types['uw_documents']->rest_controller_class = 'WP_REST_Posts_Controller';
    //faculty / staff
    $wp_post_types['uw_staff']->show_in_rest = true;
    $wp_post_types['uw_staff']->rest_base = 'staff';
    $wp_post_types['uw_staff']->rest_controller_class = 'WP_REST_Posts_Controller';
}
add_action( 'init', 'wpsd_add_cpts_args', 30 );


//*******************************************************************
//Public Post Preview - extend the expiration date of review links
//*******************************************************************
add_filter( 'ppp_nonce_life', 'my_nonce_life' );

function my_nonce_life() {
    return 60 * 60 * 24 * 180; // 6 months
}
//curl function to pull KB content
function wp_curl_fun($url) {
 $ch = curl_init();
 curl_setopt($ch, CURLOPT_URL, $url);
 curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
 $data = curl_exec($ch);
 curl_close($ch);
 return $data;
}

add_action( 'init', 'remove_custom_post_comment' );

function remove_custom_post_comment() {
    remove_post_type_support( 'projects', 'comments' );
}
if ( (isset($_GET['action']) && $_GET['action'] != 'logout') || (isset($_POST['login_location']) && !empty($_POST['login_location'])) ) {
    add_filter('login_redirect', 'my_login_redirect', 10, 3);
    function my_login_redirect() {
        $location = $_SERVER['HTTP_REFERER'];
        wp_safe_redirect($location);
        exit();
    }
}

