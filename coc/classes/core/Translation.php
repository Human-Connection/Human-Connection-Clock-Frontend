<?php
/**
 * @copyright Copyright 2019 | Human Connection gemeinnützige GmbH - Alle Rechte vorbehalten.
 * @author    Matthias Böhm <mail@matthiasboehm.com>
 */

namespace coc\core;


class Translation
{
    /**
     * Default / fallback language
     *
     * @var string
     */
    const DEFAULT_LANGUAGE = 'de';

    /**
     * File path to the translation files directory (stored in json format, e.g. de.json)
     *
     * @var string
     */
    const TRANSLATION_FILES_DIRECTORY = WP_CONTENT_DIR . '/plugins/coc/assets/translation/';

    /**
     * @var string
     */
    private $currentLanguage;

    /**
     * @var array|null
     */
    private $translationData;

    /**
     * Translation constructor.
     *
     * @param string|null $language
     */
    public function __construct($language = null)
    {
        $this->currentLanguage = self::DEFAULT_LANGUAGE;
        $this->translationData = null;

        if (empty($language)) {
            $language = $this->determineLanguageViaWPML();
        }

        if (empty($language)) {
            $language = self::DEFAULT_LANGUAGE;
        }

        $this->setLanguage($language);

        //@todo Add translation to js (via HTML source code) & translate via JS
    }

    /**
     * @param string $language
     */
    public function setLanguage($language)
    {
        if (is_string($language) && strlen($language) === 2) {
            if ($this->loadLanguageData($language) === true) {
                $this->currentLanguage = $language;
            }
        }
    }

    /**
     * @return string
     */
    public function getCurrentLanguage(): string
    {
        return $this->currentLanguage;
    }

    /**
     * @param string      $translationKey
     * @param string|null $fallbackText
     * @return string
     */
    public function translate($translationKey, $fallbackText = null)
    {
        if (is_array($this->translationData) && array_key_exists($translationKey, $this->translationData)) {
            return $this->translationData[$translationKey];
        }

        return $fallbackText ? $fallbackText : $translationKey;
    }

    /**
     * @param string      $translationKey
     * @param string|null $fallbackText
     * @return string
     */
    public function t($translationKey, $fallbackText = null)
    {
        return $this->translate($translationKey, $fallbackText);
    }

    /**
     * @param string $language
     * @return bool
     */
    private function loadLanguageData($language)
    {
        $languageFilePath = self::TRANSLATION_FILES_DIRECTORY . $language . '.json';

        if (file_exists($languageFilePath)) {
            $translationData = file_get_contents($languageFilePath);
            $this->translationData = json_decode($translationData, true);

            return true;
        }

        return false;
    }

    /**
     * Determine language via Wordpress plugin WPML
     *
     * @return string|null
     */
    private function determineLanguageViaWPML()
    {
        if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
            return ICL_LANGUAGE_CODE;
        }

        return null;
    }
}
