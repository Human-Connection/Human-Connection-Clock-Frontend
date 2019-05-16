<?php

/**
 *
 */

namespace humanconnection\core;



class BackendManager
{
	public function __construct(){
		if(is_admin()){
			add_filter('manage_hcalpha_posts_columns', [$this, 'extendAdminColumns']);
			add_action('manage_hcalpha_posts_custom_column' , [$this, 'populateColumns'], 10, 2);
		}
	}

	public function extendAdminColumns($columns){
		$columns['status'] = 'status';
		return $columns;
	}

	public function populateColumns($column, $post_id){
		switch($column){
			// display featured image for posts
			case 'status':
				echo get_post_meta($post_id, 'status', true);
				break;
		}
	}

	/**
	 * @param array      $array
	 * @param int|string $position
	 * @param mixed      $insert
	 */
	private function _arrayInsert(&$array, $position, $insert){
		if(is_int($position)){
			array_splice($array, $position, 0, $insert);
		}else{
			$pos   = array_search($position, array_keys($array));
			$array = array_merge(
				array_slice($array, 0, $pos),
				$insert,
				array_slice($array, $pos)
			);
		}
	}
}