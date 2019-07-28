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
     * @var object|null
     */
    private $countryNames;

    /**
     * @param $api
     */
    public function __construct($api)
    {
        $this->api = $api;

        $this->countryNames = $this->loadCountryNames();

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

        $html = '<div id="country-rankings">';

        $i = 0;
        foreach($countries as $country) {

            $imageSource = ClockOfChange::$pluginAssetsUri . '/images/flags/' . strtolower($country->country) . '.png';

            $html .= '<div class="country-ranking-item ' . ($i >= self::MAX_COUNTRIES ? 'hidden' : '') . '">';
            $html .= '<img class="country-flag" alt="' . $this->getCountryName($country->country) . '" title="' . $this->getCountryName($country->country) . '" src=' . $imageSource . '>';
            $html .= '<div class="country-counter">';

            $number = $country->number;
            for ($digit = 9; $digit > 0; $digit--) {
                if ($digit > strlen($number)) {
                    $html .= '<span class="digit inactive">0</span>';
                } else {
                    $html .= '<span class="digit">' . substr($number, strlen($number) - $digit, 1) . '</span>';
                }
            }

            $html .= '</div>';
            $html .= '</div>';

            $i++;
        }

        $html .= '<div id="country-rankings-load-more"><a  href="#" class="load-more-link">mehr laden <i class="fa fa-chevron-down" aria-hidden="true"></i></a></div>';

        $html .= '</div>';

        //@todo handle load more requests

        return html_entity_decode($html);
    }

    /**
     * @return object|null
     */
    private function loadCountryNames()
    {
        $countryNamesFilePath = WP_CONTENT_DIR. 'plugins/coc/assets/js/countries.json';
        if (file_exists($countryNamesFilePath)) {
            $countryNames = file_get_contents($countryNamesFilePath);
            return json_decode($countryNames);
        }

        return null;
    }

    /**
     * @param string $shortcode
     * @return string
     */
    private function getCountryName($shortcode)
    {
        $propertyName = strtoupper($shortcode);

        if (is_object($this->countryNames) && property_exists($this->countryNames, $propertyName)) {
            return $this->countryNames->$propertyName;
        }

        return '';
    }
}
