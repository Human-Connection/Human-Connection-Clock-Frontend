<?php
/**
 * Human connection Theme cpt
 *
 * Cpt Media - provides custom post type for media
 *
 */

namespace humanconnection\cpts;

class CptMedia {
	public function __construct(){}

	public function init(){
		$labels =[
			'name'               => 'HC Media',
			'singular_name'      => 'HC Media',
			'menu_name'          => 'HC Media',
			'name_admin_bar'     => 'HC Media',
			'add_new'            => 'Add New Item',
			'add_new_item'       => 'Add New Item',
			'new_item'           => 'New Media',
			'edit_item'          => 'Edit Media',
			'view_item'          => 'View Media',
			'all_items'          => 'All Media Items',
			'search_items'       => 'Search Media',
			'parent_item_colon'  => 'Parent Item:',
			'not_found'          => 'No Media Items found.',
			'not_found_in_trash' => 'No Media Items found in Trash.',
		];

		$args = [
			'labels'             => $labels,
			'description'        => 'Setup HC Media',
	        'public'             => true,
	        'publicly_queryable' => false,
	        'show_ui'            => true,
	        'show_in_menu'       => true,
	        'show_in_nav_menus'  => false,
	        'query_var'          => true,
	        'rewrite'            => false,
	        'with_front'         => false,
	        'has_archive'        => false,
	        'taxonomies'         => ['hcmedia_category'],
			'menu_icon'          => 'dashicons-video-alt2',
			'supports'           => ['title', 'editor', 'author', 'thumbnail', 'revisions']
		];
		register_post_type('hcmedia', $args);
		$this->categoryInit();
	}


	/**** CPT Category - hcmedia ****/
	public function categoryInit() {
	    $labels = [
	        'name'              => __('HC Media categories', 'Avada'),
	        'singular_name'     => __('HC Media category', 'Avada'),
	        'search_items'      => __('Search HC Media categories', 'Avada'),
	        'all_items'         => __('All HC Media categories', 'Avada'),
	        'parent_item'       => __('Parent HC Media category', 'Avada'),
	        'parent_item_colon' => __('Parent HC Media category:', 'Avada'),
	        'edit_item'         => __('Edit HC Media category', 'Avada'),
	        'update_item'       => __('Update HC Media category', 'Avada'),
	        'add_new_item'      => __('Add new HC Media category', 'Avada'),
	        'new_item_name'     => __('New HC Media category name', 'Avada'),
	        'menu_name'         => __('HC Media categories', 'Avada'),
	        'not_found'         => __('No HC Media categories found', 'Avada')
	    ];

	    // Taxonomie
	    register_taxonomy('hcmedia_category', ['hcmedia'], [
	        'labels'            => $labels,
	        'show_ui'           => true,
	        'show_admin_column' => true,
	        'show_in_nav_menus' => false,
	        'hierarchical'      => true,
	        'rewrite'           => [ 
	            'slug'          => '.',
	            'with_front'    => false 
	        ],
	    ]);
	}
}