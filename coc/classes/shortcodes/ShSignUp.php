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
        $html = '<p>„' . $this->translation->t('slogan', 'Ich glaube daran, dass Veränderung in jedem Einzelnen beginnt und dass, wenn genügend Menschen sich ändern, die Welt sich verändern wird. Ich möchte mit meinen Mitmenschen eine Brücke zu einer nachhaltigen Zukunft für die Kinder der Welt bauen.') . '”</p>';
        $html .= '<a href="#" id="joinCoC" class="cocBtn">' . $this->translation->t('joinNowButton', 'Sei dabei!') . '</a>';

        return html_entity_decode($html);
    }
}
