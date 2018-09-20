<?php
namespace coc\core;

use coc\ClockOfChange;
use coc\core\ListTable;
use coc\shortcodes\ShUserwall;

class OptionsManager
{
	const OPT_API_KEY = 'coc_api_key';
	const OPT_API_URL = 'coc_api_url';

	private $_options = null;
	public $items;

	public function __construct(){
		$this->_options = require_once(ClockOfChange::$pluginRootPath . '/config/options.php');
		$this->_initThemeOptions();
	}

	public function getOption($name){
		// ensure option is a theme option
		$key = array_keys(
			array_filter(
				$this->_options['fields'],
				function($item) use($name){
					return $item['name'] === $name;
				}
			)
		);

		if(isset($key[0]) && isset($this->_options['fields'][$key[0]])){
			return get_field($this->_options['fields'][$key[0]]['key'], 'option');
		}

		return false;
	}

	public function loadMenu(){
		$hook = add_menu_page(
			'Clock of Change entries',
			'CoC Entries',
			'manage_options',
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
							<form method="post">
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

	private function _initThemeOptions(){
		$args = [
			'page_title' => 'Clock of Change options',
			'menu_title' => 'CoC Options',
			'menu_slug' => 'coc-settings',
			'capability' => 'edit_posts',
			'position' => false,
			'parent_slug' => '',
			'icon_url' => false,
			'redirect' => true,
			'post_id' => 'options',
			'autoload' => false,
		];

		acf_add_options_page($args);
		$this->_initOptions();
	}

	private function _initOptions(){
		if($this->_options !== null)
			acf_add_local_field_group($this->_options);
	}
}