<?php

namespace humanconnection;

use humanconnection\core;
use humanconnection\admin\AdminHandler;
use humanconnection\cpts\CptAlpha;
use humanconnection\cpts\CptMedia;
use humanconnection\cpts\CptNGO;
use humanconnection\helper\Cf7Helper;

class HCTheme
{
	public static $childThemePath  = null;
	public static $childThemeUri   = null;
	public static $parentThemePath = null;
	public static $parentThemeUri  = null;

	public static $themeClasses = [];

	public static $scriptHandler  = 'scriptHandler';
	public static $avadaHelper    = 'avadaHelper';
	public static $themeCpts      = 'themeCpts';
	public static $cf7Helper      = 'cf7Helper';
	public static $backendManager = 'backendManager';

	public function __construct(){
		// ensure to call these functions only once
		self::$childThemePath  = get_stylesheet_directory();
		self::$childThemeUri   = get_stylesheet_directory_uri();
		self::$parentThemePath = get_template_directory();
		self::$parentThemeUri  = 'https://human-connection.org/wp-content/themes/Avada'; // get_template_directory_uri();

		add_filter( 'xmlrpc_enabled', '__return_false' );

		add_action('init', [$this, 'initTheme']);
		add_action('admin_init', [$this, 'adminInitTheme']);
		add_action('upload_mimes', [$this, 'setThemeMimes']);
		add_action('wp_enqueue_scripts', [$this, 'enqueueThemeScripts']);
	}

	public function initTheme(){
		self::$themeClasses[self::$scriptHandler]              = new core\ScriptHandler();
		self::$themeClasses[self::$backendManager]             = new core\BackendManager();
		self::$themeClasses[self::$avadaHelper]                = new util\AvadaHelper();
		self::$themeClasses[self::$themeCpts][CptMedia::class] = new cpts\CptMedia();
		self::$themeClasses[self::$themeCpts][CptNGO::class]   = new cpts\CptNGO();
		self::$themeClasses[self::$themeCpts][CptAlpha::class] = new cpts\CptAlpha();

		self::$themeClasses[self::$cf7Helper]                  = new helper\Cf7Helper();

		$this->_initShortcodes();

		$this->setup();
	}

	private function _initShortcodes(){
		$alphaReports = new shortcodes\AlphaReports();
		$alphaReports->init();

		$hctree = new shortcodes\HCTree();
		$hctree->init();
	}

	protected function setup(){
		remove_action( 'wp_head', 'wp_generator' );
		remove_action( 'wp_head', 'rsd_link' );
		remove_action( 'wp_head', 'wlwmanifest_link' );
		remove_action( 'wp_head', 'index_rel_link' );
		remove_action( 'wp_head', 'feed_links', 2 );
		remove_action( 'wp_head', 'feed_links_extra', 3 );
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10);
		remove_action( 'wp_head', 'wp_shortlink_wp_head', 10);

		// init cpts here
		self::$themeClasses[self::$themeCpts][CptMedia::class]->init();
		self::$themeClasses[self::$themeCpts][CptNGO::class]->init();
		self::$themeClasses[self::$themeCpts][CptAlpha::class]->init();
	}

	public function setThemeMimes($mimes = []){
			$mimes['gif'] = "image/gif";
			$mimes['pdf'] = "application/pdf";

			return $mimes;
	}

	public function adminInitTheme(){
		new AdminHandler();
	}

	public function enqueueThemeScripts(){
		self::$themeClasses[self::$scriptHandler]->init();
	}
}
