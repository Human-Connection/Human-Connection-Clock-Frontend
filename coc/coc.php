<?php
/**
 *
 * @link              https://human-connection.org
 * @since             0.1.0
 * @package           Clock of Change
 *
 * @wordpress-plugin
 * Plugin Name:  Clock of Change
 * Plugin URI:  https://human-connection.org
 * Description: display coc data
 * Author:      Sebastian Koch <mimic@42geeks.gg>
 * Version:     1.0
 * Author URI:  https://human-connection.org
 * License:      GPL-2.0+
 * License URI:  http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is accessed directory, then abort.
if(!defined('WPINC')) {
	die;
}

if(file_exists(dirname(__FILE__).'/vendor/autoload.php')){
	require_once dirname(__FILE__).'/vendor/autoload.php';
}

if(class_exists( 'coc\\ClockOfChange' )){
	$pluginRootPath = plugin_dir_path(__FILE__);
	$pluginRootUri  = plugin_dir_url(__FILE__);
	$plugin = new \coc\ClockOfChange($pluginRootPath, $pluginRootUri);

	include_once($pluginRootPath.'/vendor/acf/acf.php');

	add_filter('acf/settings/path', 'cocSetAcfSettingsPath');
	add_filter('acf/settings/dir', 'cocSetAcfSettingsDir');

	include_once($pluginRootPath.'/helper/acfinit.php');
}