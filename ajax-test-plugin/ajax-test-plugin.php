<?php
/*
Plugin Name: Ajax Test
Plugin URI:
Description: ajax test
Author: Kristina 
Version:  0.0.1
Author URI:
*/

define('ACFSURL', WP_PLUGIN_URL."/".dirname( plugin_basename( __FILE__ ) ) );
define('ACFPATH', WP_PLUGIN_DIR."/".dirname( plugin_basename( __FILE__ ) ) );
//var_dump(ACFSURL);
$css_timestamp = filemtime( get_stylesheet_directory().'/style.css' );
define( 'THEME_VERSION', $css_timestamp );

function ajax_test_plugin_demo($atts) {

	$Content = ' <form action="" method="post" id="ajax_form" name="ajax_form" class="ajax" 
enctype="multipart/form-data">

         <h1>Ajax Form</h1>

         <label><b>Name</b></label>

          <input type="text" placeholder="Enter Your Name" name="name" id="fname"
required class="name">
<input type="submit" name="ajax-submitter" id="ajax-submitter" value="AJAX TEST"> </form>';
    return $Content;
}

add_shortcode('ajax_test_plugin', 'ajax_test_plugin_demo');
//add_action( 'wp_enqueue_scripts', array( 'ajax-submitter', 'ajax-submitter' ) );

add_action( 'wp_enqueue_scripts', 'my_enqueue' );
function my_enqueue()
{
    wp_enqueue_script( 'ajax-script', ACFSURL.'/js/kristina.js', array( 'jquery' ), THEME_VERSION );

    wp_localize_script( 'ajax-script', 'settings', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        //'name' => 'Kristina',
		'action' => "send_ajax"
    ) );
}


add_action( 'wp_ajax_nopriv_send_ajax', 'send_ajax' );
add_action( 'wp_ajax_send_ajax', 'send_ajax'  );
/*
wp_localize_script( 'send_ajax', 'settings', array(
    'ajaxurl'    => admin_url( 'admin-ajax.php' ),
    'send_label' => __( 'Send AJAX', 'sendtheajax' )
) );
*/

function send_ajax() {
    //print_r("callback");
	var_dump($_POST);
    //$data = $_POST;
	$name = $_POST[‘name’];
	global $wpdb;
	$wpdb->show_errors();
	$result = $wpdb->get_results("SELECT * from wp_ajax_test_data");
	var_dump($result);
	$sql = "INSERT INTO wp_ajax_test_data (name) VALUES('$name')";
if($wpdb->query($sql)) {
	
	//if($res = $wpdb->insert(‘wp_ajax_test_data’, array(‘name’ => $name))) { 
		 print_r("inserted"); } else {
	$wpdb->print_error(); }
	//wp_send_json_success( __( 'Thanks !', 'reportajax' ) );
wp_die();
return true;
}
   
register_activation_hook( __FILE__, 'ajax_test_plugin_install' );
global $ajax_test_db_version;
$ajax_test_db_version = '0.1';
global $wpdb;

function ajax_test_plugin_install () {
 
global $wpdb;
global $ajax_test_db_version;	
$charset_collate = $wpdb->get_charset_collate();
require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

$table_name = $wpdb->prefix . "ajax_test_data";

$sql = "CREATE TABLE $table_name (
  id mediumint(9) NOT NULL AUTO_INCREMENT,
  time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
  name varchar(100) DEFAULT '' NOT NULL,
  PRIMARY KEY  (id)
) $charset_collate;";

dbDelta( $sql );
/*
$table_name = $wpdb->prefix . "skinquiz_users";

$sql = "CREATE TABLE $table_name (
  id mediumint(9) NOT NULL AUTO_INCREMENT,
  time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
  user_email varchar(100) NOT NULL,
  first_name varchar(100),
  last_name varchar(100),
  session_id varchar(100),
  PRIMARY KEY  (id)
) $charset_collate;";

dbDelta( $sql );
	*/
add_option( 'ajax_test_db_version', $ajax_test_db_version );

}


?>