<?php

namespace coc\shortcodes;

use coc\ClockOfChange;

// coc\shortcodes\shworld
class ShWorld
{
    /**
     * @var CoCAPI
     */
    private $api;

    /**
     * @var array
     */
    private $_digitClassMap = [
        0 => 'zero',
        1 => 'one',
        2 => 'two',
        3 => 'three',
        4 => 'four',
        5 => 'five',
        6 => 'six',
        7 => 'seven',
        8 => 'eight',
        9 => 'nine',
    ];

    public function __construct($api)
    {
        $this->api = $api;

        add_shortcode(strtolower(__CLASS__), [$this, 'renderShortcode']);
    }

    /**
     * Show small version of CoC (1/4 size) with parameter small="1" -> [coc\shortcodes\shworld small="1"]
     * Show medium version of CoC (1/2 size) with parameter small="1" -> [coc\shortcodes\shworld medium="1"]
     * Exclude wordpress pages by id as comma separated list -> [coc\shortcodes\shworld excludePages="25,31,75"]
     *
     * @param array $atts
     * @param       $content
     * @return string
     */
    public function renderShortcode($atts, $content)
    {
        $cocSizes = [
            'small'  => 'coc-small',
            'medium' => 'coc-medium',
        ];

        $cocSizeClass = '';
        foreach ($cocSizes as $key => $value) {
            if (isset($atts[$key]) && (string) $atts[$key] == '1') {
                $cocSizeClass = $value;
                break;
            }
        }

        if (isset($atts['excludepages'])) {
            $excludedPages = explode(',', $atts['excludepages']);
            $excludedPages = array_filter(
                $excludedPages,
                function ($value) {
                    return intval($value) > 0;
                }
            );

            if (get_queried_object_id() > 0) {
                if (in_array(get_queried_object_id(), $excludedPages)) {
                    return '';
                }
            }
        }

        $html = '<div class="hc-coc ' . $cocSizeClass . '">';
        $worldSvgImage = file_get_contents(ClockOfChange::$pluginRootPath . 'assets/images/HC-World.svg');
        $html .= sprintf('<div id="worldAnimationContainer">%s</div>', $worldSvgImage ? $worldSvgImage : '');
        $COC = ClockOfChange::app()->cocAPI()->getCount();

        $numbers = str_split($COC);
        $count   = count($numbers);
        $digits  = 8;

        // get zero digits
        $missingDigits = $digits - $count;
        $html          .= '<div id="clock">';
        $html          .= '<div class="display">';
        $html          .= '<div class="digits" data-amount="' . $COC . '">';

        for ($i = 0; $i < $missingDigits; $i++) {
            $html .= '<div class="number"><div class="zero"><span class="d1"></span><span class="d2"></span><span class="d3"></span><span class="d4"></span><span class="d5"></span><span class="d6"></span><span class="d7"></span></div>
					</div>';
        }

        for ($y = 0; $y < $count; $y++) {
            $html .= '<div class="number"><div class="';
            $html .= $this->_digitClassMap[$numbers[$y]];
            $html .= '"><span class="d1"></span><span class="d2"></span><span class="d3"></span><span class="d4"></span><span class="d5"></span><span class="d6"></span><span class="d7"></span></div></div>';
        }

        $html .= '</div><!-- .digits -->';
        $html .= '</div><!-- .clock -->';

        $html .= '</div><!-- .world-wrap -->';
        $html .= '</div><!-- .hc-coc -->';

        return html_entity_decode($html);
    }
}
