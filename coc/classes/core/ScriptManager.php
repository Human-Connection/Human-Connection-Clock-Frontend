<?php

namespace coc\core;

use coc\ClockOfChange;

class ScriptManager
{
    /**
     * @var Translation
     */
    private $translation;

    /**
     * @var Translation
     */
    public function __construct($translation)
    {
        $this->translation = $translation;

        if (!is_admin()) {
            add_action('wp_enqueue_scripts', [$this, 'initCoCOldScripts']);
            add_action('wp_enqueue_scripts', [$this, 'initCoCNewScripts']);
        }
    }

    public function initCoCNewScripts()
    {
        wp_enqueue_style(
            'coc',
            ClockOfChange::$pluginRootUri . 'assets/css/coc.css',
            []
        );

        wp_enqueue_style(
            'coc-pnotify',
            ClockOfChange::$pluginRootUri . 'assets/js/vendor/pnotify/pnotify.custom.min.css',
            []
        );

        wp_enqueue_script(
            'coc-pnotify',
            ClockOfChange::$pluginRootUri . 'assets/js/vendor/pnotify/pnotify.custom.min.js',
            ['jquery'],
            false,
            true
        );
        /*
                wp_enqueue_style(
                    'coc-select2',
                    ClockOfChange::$pluginRootUri . 'assets/css/select2.min.css',
                    []
                );

                wp_enqueue_script(
                    'coc-select2',
                    ClockOfChange::$pluginRootUri . 'assets/js/vendor/select2/select2.full.min.js',
                    ['jquery'],
                    false,
                    true
                );
        */
        wp_register_script(
            'coc-plugin',
            ClockOfChange::$pluginRootUri . 'assets/js/coc.min.js',
            [],
            null,//false,
            true
        );
        wp_enqueue_script('coc-plugin');
        wp_localize_script(
            'coc-plugin', 'cocVars', [
            'homeUrl'  => esc_url_raw(site_url()),
            'ajax_url' => esc_url_raw(rest_url()),
            'nonce'    => wp_create_nonce('wp_rest'),
            'language' => $this->translation->getCurrentLanguage(),
        ]
        );
    }

    /**
     * ScriptManager constructor for coc old scripts.
     * DO NOT CHANGE or things will break!
     */
    public function initCoCOldScripts()
    {
        wp_enqueue_style(
            'coc-old',
            ClockOfChange::$pluginRootUri . 'assets/css/cocold.css',
            []
        );

        wp_enqueue_script(
            'coc-imagesloaded',
            'https://unpkg.com/imagesloaded@4/imagesloaded.pkgd.min.js',
            [],
            false,
            true
        );

        wp_enqueue_script(
            'coc-greensock-timelinemax',
            ClockOfChange::$pluginRootUri . 'assets/js/old/greensock/minified/TimelineMax.min.js',
            [],
            false,
            true
        );

        wp_enqueue_script(
            'coc-greensock-tweenmax',
            ClockOfChange::$pluginRootUri . 'assets/js/old/greensock/minified/TweenMax.min.js',
            [],
            false,
            true
        );

        wp_enqueue_script(
            'coc-enquire',
            ClockOfChange::$pluginRootUri . 'assets/js/old/enquire.min.js',
            [],
            false,
            true
        );

        wp_enqueue_script(
            'coc-clock-animation',
            ClockOfChange::$pluginRootUri . 'assets/js/old/clockAnimation.js',
            ['jquery', 'coc-greensock-timelinemax', 'coc-greensock-tweenmax', 'coc-enquire'],
            bin2hex(random_bytes(16)),
            true
        );

        wp_enqueue_script(
            'coc-velocity',
            '//cdn.jsdelivr.net/velocity/1.5/velocity.min.js',
            ['jquery'],
            false,
            true
        );

        wp_enqueue_script(
            'coc-velocity-ui',
            '//cdn.jsdelivr.net/velocity/1.5/velocity.ui.min.js',
            ['jquery', 'coc-velocity'],
            false,
            true
        );
    }
}
