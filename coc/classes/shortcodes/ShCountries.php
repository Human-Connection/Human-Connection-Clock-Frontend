<?php
/**
 * @copyright Copyright 2019 | Human Connection gemeinnützige GmbH - Alle Rechte vorbehalten.
 * @author    Matthias Böhm <mail@matthiasboehm.com>
 */

namespace coc\shortcodes;

use coc\core\CoCAPI;

// coc\shortcodes\shuserwall
class ShCountries
{
    // Max number of countries to start with
    const MAX_COUNTRIES = 12;

    /**
     * @var CoCAPI
     */
    public $api;

    /**
     * @param $api
     */
    public function __construct($api)
    {
        $this->api = $api;

        add_shortcode(strtolower(__CLASS__), [$this, 'renderShortcode']);
    }

    /**
     *
     */
    public function renderShortcode($atts, $content)
    {
        $countries = $this->api->getCountries();
        if ($countries == null || !is_array($countries) || count($countries) === 0) {
            return '';
        }

        // @todo display countries (max number)

        //@todo handle load more requests

        $html = '';

        return html_entity_decode($html);
    }
}
