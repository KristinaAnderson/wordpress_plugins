<?php
/*
Plugin Name: Coupon Manager
Description: Adds an admin form to manage discount codes 
Author: Kristina D. Anderson
Version: 0.1
*/
ini_set('display_errors', 0);
error_reporting(0);
if ( ! defined( 'WPINC' ) ) {
    die;
}
add_action('admin_menu', 'sethi_coupon_manager_setup_menu');

function sethi_coupon_manager_setup_menu(){
    add_menu_page( 'Manage Coupon Codes', 'Manage Coupon Codes', 'manage_options', 'sethi-coupon-manager-admin', 'sethi_coupon_manager_init');
}
 
function sethi_coupon_manager_init(){
	
	$current_coupon_data = get_current_coupon_data();
	//var_dump($current_coupon_data);

	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	
	if($_POST["coupon-submitted"])  {
		//print_r("form submitted");
		$data = array('acne_percent_off' => $_POST["acne_percent_off"],'acne_coupon_code' => $_POST["acne_coupon_code"],'brightening_percent_off' => $_POST["brightening_percent_off"],'brightening_coupon_code' => $_POST["brightening_coupon_code"],'hydration_percent_off' => $_POST["hydration_percent_off"],'hydration_coupon_code' => $_POST["hydration_coupon_code"],'signature_percent_off' => $_POST["signature_percent_off"],'signature_coupon_code' => $_POST["signature_coupon_code"]);
		insert_coupon_data($data);
		//echo('<script type="text/JavaScript"> location.reload(); </script>');
		echo "<meta http-equiv='refresh' content='0'>";
	}
		
	
	echo '<div class="wrap">';
	echo '<h3>Manage Coupon Codes</h3>';
	
	echo '<form action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">';
    echo '<p>';
    echo 'Acne Percent Off  ';
    echo '<input type="text" name="acne_percent_off" id="acne_percent_off" size="10" value="'.$current_coupon_data[0]->acne_percent_off.'" />';
    echo '</p>';
    echo '<p>';
    echo 'Acne Coupon Code  ';
    echo '<input type="text" name="acne_coupon_code" id="acne_coupon_code" size="40" value="'.$current_coupon_data[0]->acne_coupon_code.'"/>';
    echo '</p>';
    echo 'Brightening Percent Off  ';
    echo '<input type="text" name="brightening_percent_off" id="brightening_percent_off" size="10" 
	value="'.$current_coupon_data[0]->brightening_percent_off.'"/>';
    echo '</p>';
    echo '<p>';
    echo 'Brightening Coupon Code  ';
    echo '<input type="text" name="brightening_coupon_code" id="brightening_coupon_code" size="40" 
	value="'.$current_coupon_data[0]->brightening_coupon_code.'"/>';
    echo '</p>';
	
	echo '<p>';
    echo 'Hydration Percent Off  ';
    echo '<input type="text" name="hydration_percent_off" id="hydration_percent_off" size="10" 
	value="'.$current_coupon_data[0]->hydration_percent_off.'"/>';
    echo '</p>';
    echo '<p>';
    echo 'Hydration Coupon Code  ';
    echo '<input type="text" name="hydration_coupon_code" id="hydration_coupon_code" size="40" 
	value="'.$current_coupon_data[0]->hydration_coupon_code.'"/>';
    echo '</p>';
    echo 'Signature Percent Off  ';
    echo '<input type="text" name="signature_percent_off" id="signature_percent_off" size="10" 
	value="'.$current_coupon_data[0]->signature_percent_off.'"/>';
    echo '</p>';
    echo '<p>';
    echo 'Signature Coupon Code  ';
    echo '<input type="text" name="signature_coupon_code" id="signature_coupon_code" size="40" 
	value="'.$current_coupon_data[0]->signature_coupon_code.'"/>';
    echo '</p>';
	
    echo '<p><input type="submit" name="coupon-submitted" id="coupon-submitted" value="Update Coupons"/></p>';
    echo '</form>';
	echo '</div>';
}

register_activation_hook(__FILE__, 'sethi_coupon_manager_install');
global $sethi_coupon_manager_db_version;
$sethi_coupon_manager_db_version = '0.1';
global $wpdb;

function insert_coupon_data($data) {
	
	global $wpdb;
	$table = $wpdb->prefix . "sethi_coupon_manager";
	
	$wpdb->insert($table, $data);
}

function get_current_coupon_data() {
	global $wpdb;
	$table = $wpdb->prefix . "sethi_coupon_manager";
	$qy = "SELECT * FROM ".$table." ORDER BY id DESC LIMIT 1";
	$result = $wpdb->get_results($qy);
	return $result;
}

function sethi_coupon_manager_install() {

global $wpdb;
global $sethi_coupon_manager_db_version;
$charset_collate = $wpdb->get_charset_collate();
require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

$table_name = $wpdb->prefix . "sethi_coupon_manager";

$sql = "CREATE TABLE $table_name (
  id mediumint(9) NOT NULL AUTO_INCREMENT,
  time datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
  acne_percent_off varchar(100),
  acne_coupon_code varchar(100),
  brightening_percent_off varchar(100),
  brightening_coupon_code varchar(100),
  hydration_percent_off varchar(100),
  hydration_coupon_code varchar(100),
  signature_percent_off varchar(100),
  signature_coupon_code varchar(100),
  PRIMARY KEY  (id)
) $charset_collate;";

dbDelta( $sql );

add_option( 'sethi_coupon_manager_db_version', $sethi_coupon_manager_db_version );

}

?>
