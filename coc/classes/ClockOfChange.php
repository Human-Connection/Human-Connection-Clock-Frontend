<?php

namespace coc;

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

use coc\core\CoCAPI;
use coc\core\OptionsManager;
use coc\core\ScriptManager;
use coc\core\Translation;
use coc\shortcodes\ShCountries;
use coc\shortcodes\ShLanguageSelector;
use coc\shortcodes\ShSign;
use coc\shortcodes\ShSignUp;
use coc\shortcodes\ShUserwall;
use coc\shortcodes\ShWorld;

class ClockOfChange
{
    public static $pluginRootPath = null;
    public static $pluginRootUri = null;
    public static $pluginAssetsPath = null;
    public static $pluginAssetsUri = null;
    public static $pluginClasses = [];

    public static $pluginRef = 'coc';
    public static $optionsManager = 'optionsManager';
    public static $scriptManager = 'scriptManager';
    public static $cocAPIManager = 'cocapiManager';
    public static $translation = 'translation';

    /**
     * ClockOfChange constructor.
     *
     * @param string $rootPath
     * @param string $rootUri
     */
    public function __construct($rootPath = '/', $rootUri = '/')
    {
        if (self::$pluginRootPath === null) {
            self::$pluginRootPath   = $rootPath;
            self::$pluginAssetsPath = $rootPath . 'assets';
        }

        if (self::$pluginRootUri === null) {
            self::$pluginRootUri   = $rootUri;
            self::$pluginAssetsUri = $rootUri . 'assets';
        }

        // add filters
        add_filter('set-screen-option', [__CLASS__, 'setScreen'], 10, 3);

        // add hooks
        add_action('plugins_loaded', [$this, 'loadCore']);
        add_action('init', [$this, 'initPlugin']);
        add_action('admin_menu', [$this, 'pluginMenu']);

        return $this;
    }

    public static function setScreen($status, $option, $value)
    {
        return $value;
    }

    public function loadCore()
    {
        self::$pluginClasses[self::$pluginRef]      = $this;
        self::$pluginClasses[self::$optionsManager] = new OptionsManager();
        self::$pluginClasses[self::$translation]    = new Translation();
        self::$pluginClasses[self::$scriptManager]  = new ScriptManager($this->translation());
        self::$pluginClasses[self::$cocAPIManager]  = new CoCAPI($this->translation());
        self::$pluginClasses[self::$cocAPIManager]->init();
    }

    public function initPlugin()
    {
        // init shortcodes
        new ShWorld($this->cocAPI());
        new ShSign($this->cocAPI());
        new ShSignUp($this->cocAPI(), $this->translation()); // need button separat for z
        new ShUserwall($this->cocAPI(), $this->translation());
        new ShCountries($this->cocAPI());
        new ShLanguageSelector($this->cocAPI(), $this->translation());
    }

    /**
     * @return ClockOfChange
     */
    public static function app()
    {
        return self::$pluginClasses[self::$pluginRef];
    }

    /**
     * @return OptionsManager
     */
    public function optionsManager()
    {
        return self::$pluginClasses[self::$optionsManager];
    }

    public function pluginMenu()
    {
        self::$pluginClasses[self::$optionsManager]->loadMenu();
    }

    /**
     * @return CoCAPI
     */
    public function cocAPI()
    {
        return self::$pluginClasses[self::$cocAPIManager];
    }

    /**
     * @return Translation
     */
    public function translation()
    {
        return self::$pluginClasses[self::$translation];
    }
}
