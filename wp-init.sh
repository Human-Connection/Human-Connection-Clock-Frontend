#!/bin/bash
wp plugin activate coc
wp option add options_coc_api_key secret
wp option add options_coc_api_url http://localhost:1337
wp post create --post_type=page --post_title='Clock of Change Frontend' --guid='clock-of-change-frontend' --post_status='publish' --post_content='<!-- wp:paragraph --><p>[coc\shortcodes\shworld]</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>[coc\shortcodes\shsign]</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>[coc\shortcodes\shsignup]</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>[coc\shortcodes\shuserwall] </p><!-- /wp:paragraph -->'
