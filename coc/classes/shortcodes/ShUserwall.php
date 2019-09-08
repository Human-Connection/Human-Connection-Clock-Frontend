<?php

namespace coc\shortcodes;

use coc\ClockOfChange;

// coc\shortcodes\shuserwall
class ShUserwall
{
    // TEXT MAX 300 characters
    const PAGE_SIZE = 81;

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
            $html .= '<div class="user-filter-element"><img src="' . home_url(
                ) . '/wp-content/plugins/coc/assets/images/filter.jpg" alt="Clock of Change Userwall Filter"></div>';
            $html .= '<div class="user-filter-element"><strong>Sortierung: </strong><label for="orderByDate">Nach Datum </label><select name="orderByDate" id="orderByDate"><option value="desc">absteigend</option><option value="asc">aufsteigend</option></select></div>';
            $html .= '<div class="user-filter-element"><strong>Filter: </strong><input type="checkbox" name="profileImage" id="profileImage"><label for="profileImage">nur Einträge mit Profilbild</label></div>';
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
            $html .= '<a id="loadMore" href="#" class="cocBtn">' . __('mehr laden') . '</a>';
            $html .= '</div>';
        }

        return html_entity_decode($html);
    }
}
