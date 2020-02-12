<?php

return [
    'key' => 'group_5e40246c76004',
    'title' => 'Clock of Change options',
    'fields' => array(
        array(
            'key' => 'field_5e4024bb167dd',
            'label' => 'Your API Key',
            'name' => 'coc_api_key',
            'type' => 'text',
            'instructions' => 'your secret key to make calls to the coc api.',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'placeholder' => 'your api key',
            'prepend' => '',
            'append' => '',
            'maxlength' => 64,
        ),
        array(
            'key' => 'field_5e4024e8167de',
            'label' => 'API base url',
            'name' => 'coc_api_url',
            'type' => 'text',
            'instructions' => 'the url to send requests to',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'placeholder' => 'your api url (without trailing slash)',
            'prepend' => '',
            'append' => '',
            'maxlength' => 255,
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'options_page',
                'operator' => '==',
                'value' => 'coc-settings',
            ),
        ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'left',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => 1,
    'description' => '',
];
