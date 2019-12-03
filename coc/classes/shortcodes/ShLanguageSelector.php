<?php
/**
 * @copyright Copyright 2019 | Human Connection gemeinnützige GmbH - Alle Rechte vorbehalten.
 * @author    Matthias Böhm <mail@matthiasboehm.com>
 */

namespace coc\shortcodes;

use coc\ClockOfChange;
use coc\core\CoCAPI;
use coc\core\Translation;

// coc\shortcodes\shlanguageselector
class ShLanguageSelector
{
    // Max number of countries to start with
    const MAX_COUNTRIES = 12;

    /**
     * @var CoCAPI
     */
    private $api;

    /**
     * @var array
     */
    private $languages;

    /**
     * @var array
     */
    private $availableTranslations;

    /**
     * @param CoCAPI      $api
     * @param Translation $translation
     */
    public function __construct($api, $translation)
    {
        $this->api         = $api;
        $this->translation = $translation;

        $this->availableTranslations = $this->loadAvailableTranslations();
        $this->languages             = $this->loadLanguages();

        add_shortcode(strtolower(__CLASS__), [$this, 'renderShortcode']);
    }

    /**
     * @param $atts
     * @param $content
     * @return string
     */
    public function renderShortcode($atts, $content)
    {
        $imageSourceString = ClockOfChange::$pluginAssetsUri . '/images/flags/%s.png';

        $html = ' <div id="language-selector">';

        $currentLanguage = $this->getLanguage($this->translation->currentLanguage);
        $html            .= '<button class="language-selector-dropbtn"><img src="' . sprintf(
                $imageSourceString, $this->translation->currentLanguage
            )
            . '" alt="' . $currentLanguage . '"> ' . $currentLanguage . ' <i class="fas fa-chevron-down"></i></button>';


        $html .= '<div class="language-selector-dropdown-content">';
        foreach ($this->availableTranslations as $availableTranslation) {
            if ($availableTranslation !== $this->translation->currentLanguage) {

                $language = $this->getLanguage($availableTranslation);
                $html     .= '<a href="?lang=' . $availableTranslation . '" class="language-selector-link"><img src="' . sprintf($imageSourceString, $availableTranslation)
                    . '" alt="' . $language . '">' . $language . '</a>';
            }
        }

        $html .= '</div>';
        $html .= '</div> ';


        return html_entity_decode($html);
    }

    /**
     * @return array
     */
    private function loadAvailableTranslations()
    {
        $translationPath  = WP_CONTENT_DIR . '/plugins/coc/assets/translation';
        $translationFiles = scandir($translationPath);

        $availableLanguages = [Translation::DEFAULT_LANGUAGE];
        if ($translationFiles) {
            $translationFiles   = array_filter(
                $translationFiles, function ($value) {
                return $value !== '.' && $value !== '..' && strpos($value, '.json') && $value !== 'languages.json';
            }
            );
            $availableLanguages = array_map(
                function ($value) {
                    $parts = explode('.', $value);

                    return $parts[0];
                }, $translationFiles
            );
        };

        return array_unique($availableLanguages);
    }

    /**
     * @return object|null
     */
    private function loadLanguages()
    {
        $languagesFilePath = WP_CONTENT_DIR . '/plugins/coc/assets/translation/languages.json';
        if (file_exists($languagesFilePath)) {
            $languages = file_get_contents($languagesFilePath);

            return json_decode($languages, true);
        }

        return null;
    }

    /**
     * @param string $shortcode
     * @return string
     */
    private function getLanguage($shortcode)
    {
        $shortcode = strtolower($shortcode);

        if (array_key_exists($shortcode, $this->languages)) {
            return $this->languages[$shortcode];
        }

        return '';
    }
}
