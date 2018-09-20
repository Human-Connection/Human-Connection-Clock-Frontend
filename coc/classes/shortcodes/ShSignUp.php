<?php
namespace coc\shortcodes;

use coc\ClockOfChange;
// coc\shortcodes\shsignup
class ShSignUp
{
	public function __construct(){
		add_shortcode(strtolower(__CLASS__), [$this, 'renderShortcode']);
	}

	/**
	 *
	 */
	public function renderShortcode($atts, $content){
		$html = '';
		$html .= '<a href="#" id="joinCoC" class="cocBtn">Sei dabei!</a>';

		return html_entity_decode($html);
	}
}