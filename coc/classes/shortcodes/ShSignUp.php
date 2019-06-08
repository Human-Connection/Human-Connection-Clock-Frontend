<?php

namespace coc\shortcodes;

// coc\shortcodes\shsignup
class ShSignUp
{
    /**
     * @var CoCAPI
     */
    private $api;

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
        $html = '';
        $html .= '<a href="#" id="joinCoC" class="cocBtn">Sei dabei!</a>';

        return html_entity_decode($html);
    }
}
