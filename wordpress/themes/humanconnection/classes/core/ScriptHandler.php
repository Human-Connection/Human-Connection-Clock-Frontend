<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 22.11.2017
 * Time: 01:44
 */

namespace humanconnection\core;

use humanconnection\HCTheme;

class ScriptHandler {
	public function __construct(){
		add_filter('mce_buttons_2', [$this, 'enableMCEFonts']);
		add_filter('tiny_mce_before_init', [$this, 'overwriteFontSizes']);
	}

	public function init(){
		wp_enqueue_style('humanconnection', HCTheme::$childThemeUri.'/style.css', ['avada-stylesheet']);
        wp_enqueue_style('fusion-styles', HCTheme::$childThemeUri.'/assets/css/fusion_styles.css');
		wp_enqueue_script('humanconnection', HCTheme::$childThemeUri.'/assets/js/hcfrontend.js', ['jquery'], false, true);

		wp_enqueue_style('hc-tree', HCTheme::$childThemeUri.'/assets/css/hctree.css', ['humanconnection']);
		//wp_enqueue_script('hc-tree', HCTheme::$childThemeUri.'/assets/js/hctree.js', ['jquery'], false, true);
	}

	public function enableMCEFonts($buttons){
		array_unshift($buttons, 'fontselect'); // Add Font Select
		array_unshift($buttons, 'fontsizeselect'); // Add Font Size Select
		return $buttons;
	}

	public function overwriteFontSizes($initArray){
		$initArray['fontsize_formats'] = "9px 10px 12px 13px 14px 16px 18px 21px 24px 28px 32px 36px";
		return $initArray;
	}
}
