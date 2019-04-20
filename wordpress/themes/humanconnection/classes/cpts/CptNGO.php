<?php
/**
 * Human connection Theme cpt
 *
 * Cpt NGO - provides custom post type for NGOs
 *
 */

namespace humanconnection\cpts;

class CptNGO {
	public function __construct(){
		if(current_user_can('manage_options')){
			add_filter( 'views_edit-hcngos', [$this, 'downloadBtn']);
		}
	}

	public function downloadBtn($btns){
		$btns['export'] = '<a target="_blank" href="?ngoex=2" id="export" type="button"  title="Export NGOs" style="margin:5px">Export NGOs</a>';
		return $btns;
	}

	public function init(){
		$labels =[
			'name'               => 'NGOs',
			'singular_name'      => 'NGO',
			'menu_name'          => 'NGOs',
			'name_admin_bar'     => 'NGOs',
			'add_new'            => 'Add New NGO',
			'add_new_item'       => 'Add New NGO',
			'new_item'           => 'New NGO',
			'edit_item'          => 'Edit NGO',
			'view_item'          => 'View NGO',
			'all_items'          => 'All NGOs',
			'search_items'       => 'Search NGOs',
			'parent_item_colon'  => 'Parent NGO:',
			'not_found'          => 'No NGOs found.',
			'not_found_in_trash' => 'No NGOs found in Trash.',
		];

		$args = [
			'labels'             => $labels,
			'description'        => 'Setup NGOs',
			'public'             => true,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'show_in_nav_menus'  => false,
			'query_var'          => true,
			'rewrite'            => false,
			'with_front'         => false,
			'has_archive'        => false,
			'taxonomies'         => [],
			'menu_icon'          => 'dashicons-smiley',
			'supports'           => ['title', 'author', 'revisions']
		];
		register_post_type('hcngos', $args);
	}
}