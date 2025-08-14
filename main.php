<?php

/**
 * Plugin Name: Woocommerce Event Manager Addon: UID Number / BIB Number
 * Plugin URI: http://mage-people.com
 * Description: UID Number / BIB Number Feature for Woocommerce Event
 * Version: 1.0.0
 * Author: MagePeople Team
 * Author URI: http://www.mage-people.com/
 * Text Domain: mage-eventpress-uid-number
 * Domain Path: /languages/
 */

if (!defined('ABSPATH')) {
  die;
} // Cannot access pages directly.
include_once(ABSPATH . 'wp-admin/includes/plugin.php');
if (is_plugin_active('woocommerce/woocommerce.php') && is_plugin_active('mage-eventpress/woocommerce-event-press.php')) {


	// if (!defined('MEP_STORE_URL')) { 
	// 	define('MEP_STORE_URL', 'https://mage-people.com/');
	// }	
	// define('MEP_UID_ID', 85348);
	// define('MEP_UID_NAME', 'Woocommerce Event Manager Addon: Waitlist');
	// define('EDD_TAB_NAME', 'Waitlist');

	// if (!class_exists('EDD_SL_Plugin_Updater')) {
	// 	include(dirname(__FILE__) . '/license/EDD_SL_Plugin_Updater.php');
	// }
	// include(dirname(__FILE__) . '/license/main.php');
	// 	$license_key      	= trim(get_option('mEP_UID_license_key'));
	// 	$edd_updater 		= new EDD_SL_Plugin_Updater(MEP_STORE_URL, __FILE__, array(
	// 	'version'     		=> '1.0.0',
	// 	'license'     		=> $license_key,
	// 	'item_name'   		=> MEP_UID_NAME,
	// 	'item_id'     		=> MEP_UID_ID,
	// 	'author'      		=> 'MagePeople Team',
	// 	'url'         		=> home_url(),
	// 	'beta'        		=> false
	// 	));

  require_once(dirname(__FILE__) . "/inc/file_include.php");

} else {

  function mEP_UID_admin_notice_wc_not_active()
  {
    $class = 'notice notice-error';
    $message = __('Woocommerce Event Manager Addon: UID Number / BIB Numberr', 'mage-eventpress-waitlist');
    printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html($message));
  }
  add_action('admin_notices', 'mEP_UID_admin_notice_wc_not_active');
}
