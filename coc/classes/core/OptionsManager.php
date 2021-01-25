<?php
namespace coc\core;

use coc\ClockOfChange;
use coc\shortcodes\ShUserwall;

class OptionsManager
{
	const OPT_API_KEY = 'coc_api_key';
	const OPT_API_URL = 'coc_api_url';
    const OPT_RECAPTCHA_SITE_KEY = 'coc_recaptcha_site_key';
    const OPT_RECAPTCHA_SECRET_KEY = 'coc_recaptcha_secret_key';
    const OPT_CLEVERREACH_CLIENT_ID = 'coc_cleverreach_client_id';
    const OPT_CLEVERREACH_CLIENT_SECRET = 'coc_cleverreach_client_secret';
    const OPT_CLEVERREACH_REFRESH_TOKEN = 'coc_cleverreach_refresh_token';
    const OPT_CLEVERREACH_ACCESS_TOKEN = 'coc_cleverreach_access_token';
    const OPT_CLEVERREACH_GROUP_ID = 'coc_cleverreach_group_id';

    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

	// Custom options that don't rely on Advanced Custom Fields Plugin
	private $customOptions = null;

	public $items;

	public function __construct(){

		if (file_exists(ClockOfChange::$pluginRootPath . '/config/custom.php')) {
            $this->customOptions = require_once(ClockOfChange::$pluginRootPath . '/config/custom.php');
        }

        $this->options = get_option( 'coc_settings' );

        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
	}

	public function getOption($name){
        if (!empty($this->options) && isset($this->options[$name])) {
            return $this->options[$name];
        }

		return false;
	}

	public function loadMenu(){
		$hook = add_menu_page(
			'Clock of Change entries',
			'CoC Entries',
			'manage_categories',
			'coc_entries',
			[$this, 'pluginSettingsPage']
		);

		add_action( "load-$hook", [ $this, 'screenOption' ] );
	}

	/**
	 * Screen options
	 */
	public function screenOption() {

		$option = 'per_page';
		$args   = [
			'label'   => 'Entries',
			'default' => ShUserwall::PAGE_SIZE,
			'option'  => 'entries_per_page'
		];

		add_screen_option($option, $args);

		// LOAD LIST HERE
		$this->items = new ListTable();
	}

	/**
	 * Plugin settings page
	 */
	public function pluginSettingsPage() {
		?>
		<div class="wrap">
			<h2>Clock of Change</h2>

			<div id="coc-users-list">
				<div id="post-body" class="metabox-holder columns-2">
					<div id="post-body-content">
						<div class="meta-box-sortables ui-sortable">
							<form method="get">
                                <input type="hidden" id="page" name="page" value="<?= esc_attr($_REQUEST['page']); ?>">
                                <input type="hidden" id="page" name="_wp_http_referer" value="">
								<?php
								$this->items->prepare_items();
								$this->items->display(); ?>
							</form>
						</div>
					</div>
				</div>
				<br class="clear">
			</div>
		</div>
		<?php
	}

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_menu_page(
            'Settings Admin',
            'CoC Options',
            'manage_options',
            'coc-setting-admin',
            array( $this, 'create_admin_page' ),
            '',
            105
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'coc_settings' );
        ?>
        <div class="wrap">
            <h1>My Settings</h1>
            <form method="post" action="options.php">
                <?php
                // This prints out all hidden setting fields
                settings_fields( 'coc_settings_group' );
                do_settings_sections( 'coc-setting-admin' );
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {
        register_setting(
            'coc_settings_group', // Option group
            'coc_settings', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            'HC Clock Settings', // Title
            array( $this, 'print_section_info' ), // Callback
            'coc-setting-admin' // Page
        );

        add_settings_field(
            self::OPT_API_KEY, // ID
            'Your API Key', // Title
            array( $this, 'apiKeyTextField' ), // Callback
            'coc-setting-admin', // Page
            'setting_section_id' // Section
        );

        add_settings_field(
            self::OPT_API_URL,
            'API base url',
            array( $this, 'apiUrlTextField' ),
            'coc-setting-admin',
            'setting_section_id'
        );

        add_settings_section(
            'setting_recaptcha_section_id', // ID
            'Google Recaptcha v2 Settings', // Title
            array( $this, 'print_section_info' ), // Callback
            'coc-setting-admin' // Page
        );


        add_settings_field(
            self::OPT_RECAPTCHA_SITE_KEY,
            'Recaptcha v2 Site Key',
            array( $this, 'recaptchaSiteKeyTextField' ),
            'coc-setting-admin',
            'setting_recaptcha_section_id'
        );

        add_settings_field(
            self::OPT_RECAPTCHA_SECRET_KEY,
            'Recaptcha v2 Secret Key',
            array( $this, 'recaptchaSecretKeyTextField' ),
            'coc-setting-admin',
            'setting_recaptcha_section_id'
        );

        add_settings_section(
            'setting_cleverreach_section_id', // ID
            'CleverReach Newsletter API Settings', // Title
            array( $this, 'print_section_info' ), // Callback
            'coc-setting-admin' // Page
        );

        add_settings_field(
            self::OPT_CLEVERREACH_CLIENT_ID,
            'CleverReach API ClientId',
            array( $this, 'cleverReachClientId' ),
            'coc-setting-admin',
            'setting_cleverreach_section_id'
        );

        add_settings_field(
            self::OPT_CLEVERREACH_CLIENT_SECRET,
            'CleverReach API Client Secret',
            array( $this, 'cleverReachClientSecret' ),
            'coc-setting-admin',
            'setting_cleverreach_section_id'
        );

        add_settings_field(
            self::OPT_CLEVERREACH_REFRESH_TOKEN,
            'CleverReach API Refresh Token',
            array( $this, 'cleverReachRefreshToken' ),
            'coc-setting-admin',
            'setting_cleverreach_section_id'
        );

        add_settings_field(
            self::OPT_CLEVERREACH_ACCESS_TOKEN,
            'CleverReach Access Token',
            array( $this, 'cleverReachAccessToken' ),
            'coc-setting-admin',
            'setting_cleverreach_section_id'
        );


        add_settings_field(
            self::OPT_CLEVERREACH_GROUP_ID,
            'CleverReach Group Id',
            array( $this, 'cleverReachGroupId' ),
            'coc-setting-admin',
            'setting_cleverreach_section_id'
        );
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input[self::OPT_API_KEY] ) ) {
            $new_input[self::OPT_API_KEY] = sanitize_text_field( $input[self::OPT_API_KEY] );
        }

        if( isset( $input[self::OPT_API_URL] ) ) {
            $new_input[self::OPT_API_URL] = sanitize_text_field( $input[self::OPT_API_URL] );
        }

        if( isset( $input[self::OPT_RECAPTCHA_SITE_KEY] ) ) {
            $new_input[self::OPT_RECAPTCHA_SITE_KEY] = sanitize_text_field( $input[self::OPT_RECAPTCHA_SITE_KEY] );
        }

        if( isset( $input[self::OPT_RECAPTCHA_SECRET_KEY] ) ) {
            $new_input[self::OPT_RECAPTCHA_SECRET_KEY] = sanitize_text_field( $input[self::OPT_RECAPTCHA_SECRET_KEY] );
        }

        if( isset( $input[self::OPT_CLEVERREACH_CLIENT_ID] ) ) {
            $new_input[self::OPT_CLEVERREACH_CLIENT_ID] = sanitize_text_field( $input[self::OPT_CLEVERREACH_CLIENT_ID] );
        }

        if( isset( $input[self::OPT_CLEVERREACH_CLIENT_SECRET] ) ) {
            $new_input[self::OPT_CLEVERREACH_CLIENT_SECRET] = sanitize_text_field( $input[self::OPT_CLEVERREACH_CLIENT_SECRET] );
        }

        if( isset( $input[self::OPT_CLEVERREACH_REFRESH_TOKEN] ) ) {
            $new_input[self::OPT_CLEVERREACH_REFRESH_TOKEN] = sanitize_text_field( $input[self::OPT_CLEVERREACH_REFRESH_TOKEN] );
        }


        if( isset( $input[self::OPT_CLEVERREACH_ACCESS_TOKEN] ) ) {
            $new_input[self::OPT_CLEVERREACH_ACCESS_TOKEN] = sanitize_text_field( $input[self::OPT_CLEVERREACH_ACCESS_TOKEN] );
        }

        if( isset( $input[self::OPT_CLEVERREACH_GROUP_ID] ) ) {
            $new_input[self::OPT_CLEVERREACH_GROUP_ID] = sanitize_text_field( $input[self::OPT_CLEVERREACH_GROUP_ID] );
        }

        return $new_input;
    }

    /**
     * Print the Section text
     */
    public function print_section_info()
    {
        // print 'Enter your settings below:';
    }


    public function apiKeyTextField()
    {

        printf(
            '<input type="text" id="%s" name="coc_settings[%s]" value="%s" />',
            self::OPT_API_KEY,
            self::OPT_API_KEY,
            isset( $this->options[self::OPT_API_KEY] ) ? esc_attr( $this->options[self::OPT_API_KEY]) : ''
        );
    }

    public function apiUrlTextField()
    {
        printf(
            '<input type="text" id="%s" name="coc_settings[%s]" value="%s" />',
            self::OPT_API_URL,
            self::OPT_API_URL,
            isset( $this->options[self::OPT_API_URL] ) ? esc_attr( $this->options[self::OPT_API_URL]) : ''
        );
    }

    public function recaptchaSiteKeyTextField()
    {
        printf(
            '<input type="text" id="%s" name="coc_settings[%s]" value="%s" />',
            self::OPT_RECAPTCHA_SITE_KEY,
            self::OPT_RECAPTCHA_SITE_KEY,
            isset( $this->options[self::OPT_RECAPTCHA_SITE_KEY] ) ? esc_attr( $this->options[self::OPT_RECAPTCHA_SITE_KEY]) : ''
        );
    }

    public function recaptchaSecretKeyTextField()
    {
        printf(
            '<input type="text" id="%s" name="coc_settings[%s]" value="%s" />',
            self::OPT_RECAPTCHA_SECRET_KEY,
            self::OPT_RECAPTCHA_SECRET_KEY,
            isset( $this->options[self::OPT_RECAPTCHA_SECRET_KEY] ) ? esc_attr( $this->options[self::OPT_RECAPTCHA_SECRET_KEY]) : ''
        );
    }

    public function cleverReachClientId()
    {
        printf(
            '<input type="text" id="%s" name="coc_settings[%s]" value="%s" />',
            self::OPT_CLEVERREACH_CLIENT_ID,
            self::OPT_CLEVERREACH_CLIENT_ID,
            isset( $this->options[self::OPT_CLEVERREACH_CLIENT_ID] ) ? esc_attr( $this->options[self::OPT_CLEVERREACH_CLIENT_ID]) : ''
        );
    }

    public function cleverReachClientSecret()
    {
        printf(
            '<input type="text" id="%s" name="coc_settings[%s]" value="%s" />',
            self::OPT_CLEVERREACH_CLIENT_SECRET,
            self::OPT_CLEVERREACH_CLIENT_SECRET,
            isset( $this->options[self::OPT_CLEVERREACH_CLIENT_SECRET] ) ? esc_attr( $this->options[self::OPT_CLEVERREACH_CLIENT_SECRET]) : ''
        );
    }

    public function cleverReachRefreshToken()
    {
        printf(
            '<input type="text" id="%s" name="coc_settings[%s]" value="%s" />',
            self::OPT_CLEVERREACH_REFRESH_TOKEN,
            self::OPT_CLEVERREACH_REFRESH_TOKEN,
            isset( $this->options[self::OPT_CLEVERREACH_REFRESH_TOKEN] ) ? esc_attr( $this->options[self::OPT_CLEVERREACH_REFRESH_TOKEN]) : ''
        );
    }

    public function cleverReachAccessToken()
    {
        printf(
            '<input type="text" id="%s" name="coc_settings[%s]" value="%s" />',
            self::OPT_CLEVERREACH_ACCESS_TOKEN,
            self::OPT_CLEVERREACH_ACCESS_TOKEN,
            isset( $this->options[self::OPT_CLEVERREACH_ACCESS_TOKEN] ) ? esc_attr( $this->options[self::OPT_CLEVERREACH_ACCESS_TOKEN]) : ''
        );
    }

    public function cleverReachGroupId()
    {
        printf(
            '<input type="text" id="%s" name="coc_settings[%s]" value="%s" />',
            self::OPT_CLEVERREACH_GROUP_ID,
            self::OPT_CLEVERREACH_GROUP_ID,
            isset( $this->options[self::OPT_CLEVERREACH_GROUP_ID] ) ? esc_attr( $this->options[self::OPT_CLEVERREACH_GROUP_ID]) : ''
        );
    }
}
