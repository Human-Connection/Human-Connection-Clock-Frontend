<?php

namespace coc\shortcodes;

use coc\ClockOfChange;
use coc\core\CoCAPI;
use coc\core\Translation;

// coc\shortcodes\shuserwall
class ShUserwall
{
    // TEXT MAX 300 characters
    const PAGE_SIZE = 81;

    /**
     * @var CoCAPI
     */
    private $api;

    /**
     * @var Translation
     */
    private $translation;

    /**
     * ShUserwall constructor.
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

        $users = ClockOfChange::app()->cocAPI()->getUsers();
        if (!empty($users) && isset($users->results)) {
            $html .= '<div id="user-message">';
            $html .= '<div class="close-wrapper">';
            $html .= '<i class="fas fa-times"></i>';
            $html .= '</div>';
            $html .= '<div class="user-message-text">';
            $html .= '<figure class="message-image-wrapper">';
            $html .= '<img class="user-message-image" src="" />';
            $html .= '</figure>';
            $html .= '<p class="message-name"></p>';
            $html .= '<p class="message-text"></p>';
            $html .= '<span class="message-country"></span>';
            $html .= '</div>';
            $html .= '<div class="message-controls">';
            $html .= '<div class="left-arrow-wrapper"><i class="fas fa-arrow-left" id="prevMessage"></i></div>';
            $html .= '<div class="right-arrow-wrapper"><i class="fas fa-arrow-right" id="nextMessage"></i></div>';
            $html .= '</div>';
            $html .= '</div>';

            $html .= '<div id="user-filter">';
            $html .= '<div class="user-filter-element"><img src="' . esc_url_raw(site_url())
                . '/wp-content/plugins/coc/assets/images/filter.jpg" alt="Clock of Change Userwall Filter"></div>';
            $html .= '<div class="user-filter-element"><strong>' . $this->translation->t(
                    'sort', 'Sortierung'
                ) . ': </strong><label for="orderByDate">' . $this->translation->t(
                    'sortByDate', 'Nach Datum'
                ) . ' </label><select name="orderByDate" id="orderByDate"><option value="desc">' . $this->translation->t(
                    'descending', 'absteigend'
                ) . '</option><option value="asc">' . $this->translation->t(
                    'ascending', 'aufsteigend'
                ) . '</option></select></div>';
            $html .= '<div class="user-filter-element"><strong>' . $this->translation->t(
                    'filter', 'Filter'
                ) . ': </strong><input type="checkbox" name="profileImage" id="profileImage"><label for="profileImage">' . $this->translation->t(
                    'filterByProfileImage', 'nur Einträge mit Profilbild'
                ) . '</label></div>';
            $html .= '</div>';

            $html .= '<div class="user-container" id="user-list">';
            foreach ($users->results as $user) {
                $html .= '<div class="user-item">';
                // use placeholder if user has no image
                if ($user->image !== '') {
                    $src = $user->image;
                } else {
                    $src = ClockOfChange::$pluginAssetsUri . '/images/coc-placeholder.jpg';
                }

                $uName = $user->firstname . ' ' . $user->lastname;
                $html  .= '<img class="user-image" data-anon="' . $user->anon . '" data-uname="' . $uName . '" '
                    . 'data-message="' . $user->message . '" data-country="' . $user->country . '" '
                    . 'style="width:100%;margin-top:5px;" alt="signer-image" src="' . $src . '" />';

                $html .= '</div>';
            }
            $html .= '</div>';
            $html .= '<div class="load-more-btn">';
            $html .= '<a id="loadMore" href="#" class="cocBtn">' . __(
                    $this->translation->t('loadMore', 'mehr laden')
                ) . '</a>';
            $html .= '</div>';
        }

        return html_entity_decode($html);
    }
}
