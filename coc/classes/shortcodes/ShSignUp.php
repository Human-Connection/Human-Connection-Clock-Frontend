<?php

namespace coc\shortcodes;

use coc\core\CoCAPI;
use coc\core\Translation;

// coc\shortcodes\shsignup
class ShSignUp
{
    /**
     * @var CoCAPI
     */
    private $api;

    /**
     * @var Translation
     */
    private $translation;

    /**
     * ShSignUp constructor.
     *
     * @param CoCAPI      $api
     * @param Translation $translation
     */
    public function __construct($api, $translation)
    {
        $this->api         = $api;
        $this->translation = $translation;

        add_shortcode(strtolower(__CLASS__), [$this, 'renderShortcode']);
    }

    /**
     *
     */
    public function renderShortcode($atts, $content)
    {
        $html = '';
        $html .= '<a href="#" id="joinCoC" class="cocBtn">Sei dabei!</a>';

        return html_entity_decode($html);
    }
}
