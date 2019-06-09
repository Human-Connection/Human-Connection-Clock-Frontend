<?php
/**
 * @copyright Copyright 2019 | Human Connection gemeinnützige GmbH - Alle Rechte vorbehalten.
 * @author    Matthias Böhm <mail@matthiasboehm.com>
 */

namespace coc\shortcodes;

use coc\ClockOfChange;
use coc\core\CoCAPI;

// coc\shortcodes\shuserwall
class ShCountries
{
    // Max number of countries to start with
    const MAX_COUNTRIES = 12;

    /**
     * @var CoCAPI
     */
    private $api;

    /**
     * @param $api
     */
    public function __construct($api)
    {
        $this->api = $api;

        add_shortcode(strtolower(__CLASS__), [$this, 'renderShortcode']);
    }

    /**
     * @param $atts
     * @param $content
     * @return string
     */
    public function renderShortcode($atts, $content)
    {
        $countries = $this->api->getCountries();
        if ($countries == null || !is_array($countries) || count($countries) === 0) {
            return '';
        }

        $maxNumber = count($countries) < self::MAX_COUNTRIES ? count($countries) : self::MAX_COUNTRIES;

        // @todo display countries (max number)

        $html = '';

        $html .= '<div id="country-rankings">';

        for ($i = 0; $i < $maxNumber; $i++) {

            $imageSource = ClockOfChange::$pluginAssetsUri . '/images/flags/' . $countries[$i]->country . '.png';

            $html .= '<div class="country-ranking-item">';
            $html .= '<img class="country-flag" alt="country flag" src=' . $imageSource . '>';
            $html .= '<div class="country-counter">';

            $number = $countries[$i]->number;
            var_dump(substr($number, 1, 1));
            for ($digit = 0; $digit < 9; $digit++) {
                if ($digit > strlen($number)) {
                    $html .= '<span class="digit inactive">0</span>';
                } else {
                    $html .= '<span class="digit">' . substr($number, 1, 1) . '</span>';
                }
            }

            $html .= '</div>';
            $html .= '</div>';
        }

        $html .= '</div>';

        //@todo handle load more requests


        return html_entity_decode($html);
    }
}
