<?php
/**
 * Human connection Theme cpt
 *
 * Cpt Alpha - provides custom post type for alpha reports by users
 *
 */

namespace humanconnection\cpts;

class CptAlpha {
	const CPT_NAME = 'hcalpha';

	public function __construct(){}

	public function init(){
		$labels =[
			'name'               => 'HC Alpha',
			'singular_name'      => 'HC Alpha',
			'menu_name'          => 'HC Alpha',
			'name_admin_bar'     => 'HC Alpha',
			'add_new'            => 'Add New Item',
			'add_new_item'       => 'Add New Item',
			'new_item'           => 'New Alpha entry',
			'edit_item'          => 'Edit Alpha entry',
			'view_item'          => 'View Alpha entry',
			'all_items'          => 'All Alpha Items',
			'search_items'       => 'Search Alpha',
			'parent_item_colon'  => 'Parent Item:',
			'not_found'          => 'No Alpha Items found.',
			'not_found_in_trash' => 'No Alpha Items found in Trash.',
		];

		$args = [
			'labels'             => $labels,
			'description'        => 'Setup HC Alpha',
	        'public'             => true,
	        'publicly_queryable' => false,
	        'show_ui'            => true,
	        'show_in_menu'       => true,
	        'show_in_nav_menus'  => false,
	        'query_var'          => true,
	        'rewrite'            => false,
	        'with_front'         => false,
	        'has_archive'        => false,
	        'taxonomies'         => [self::CPT_NAME.'_category'],
			'menu_icon'          => 'dashicons-editor-textcolor',
			'supports'           => ['title', 'editor', 'author', 'revisions']
		];
		register_post_type(self::CPT_NAME, $args);
		$this->categoryInit();
	}


	/**** CPT Category - hcmedia ****/
	public function categoryInit() {
	    $labels = [
	        'name'              => __('HC Alpha categories', 'Avada'),
	        'singular_name'     => __('HC Alpha category', 'Avada'),
	        'search_items'      => __('Search HC Alpha categories', 'Avada'),
	        'all_items'         => __('All HC Alpha categories', 'Avada'),
	        'parent_item'       => __('Parent HC Alpha category', 'Avada'),
	        'parent_item_colon' => __('Parent HC Alpha category:', 'Avada'),
	        'edit_item'         => __('Edit HC Alpha category', 'Avada'),
	        'update_item'       => __('Update HC Alpha category', 'Avada'),
	        'add_new_item'      => __('Add new HC Alpha category', 'Avada'),
	        'new_item_name'     => __('New HC Alpha category name', 'Avada'),
	        'menu_name'         => __('HC Alpha categories', 'Avada'),
	        'not_found'         => __('No HC Alpha categories found', 'Avada')
	    ];

	    // Taxonomie
	    register_taxonomy(self::CPT_NAME.'_category', [self::CPT_NAME], [
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