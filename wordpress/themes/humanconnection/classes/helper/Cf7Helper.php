<?php
/**
 * Human connection Theme helper Class
 *
 * Cf7 Helper - provides helper functions for contact form 7 plugin
 *
 */

namespace humanconnection\helper;

use humanconnection\cpts\CptAlpha;

class Cf7Helper {
	private $_storeFormIds = [
		14178 => [
			'name'      => 'alphaFormDE',
			'post_type' => CptAlpha::CPT_NAME
		],
		30619 => [
			'name'      => 'alphaFormEN',
			'post_type' => CptAlpha::CPT_NAME
		]
	];

	public function __construct(){
		add_filter('wpcf7_posted_data', [$this, 'storeCf7Data']);
	}

	public function storeCf7Data($posted_data){
		if(isset($posted_data['_wpcf7'])){
			if(array_key_exists($posted_data['_wpcf7'], $this->_storeFormIds)){
				$args = [
					'post_type' => $this->_storeFormIds[
						intval($posted_data['_wpcf7'])
					]['post_type']
				];

				if($this->_isCptAlpha($posted_data)){
					// get fields for cpt alpha
					$postData = $this->_buildPost($posted_data);
					$post = array_merge($args, $postData);

					// save post
					$id = wp_insert_post($post);
					if($id > 0 && !is_wp_error($id)){
						// save related meta
						update_field('reporterName', $post['reporterName'], $id);
						update_field('reporterEmail', $post['reporterEmail'], $id);
						update_field('time', $post['time'], $id);
					}
				}
			}
		}

		return $posted_data;
	}

	private function _buildPost($data, $cpt = CptAlpha::CPT_NAME){
		if(!empty($data) && $cpt === CptAlpha::CPT_NAME){
			return [
				'reporterName'  => $data['reporterName'] ?? '',
				'reporterEmail' => $data['reporterEmail'] ?? '',
				'post_title'    => $data['title'] ?? '',
				'post_content'  => $data['description'] ?? '',
				'time'          => $data['time'] ?? '',
			];
		}
	}

	private function _isCptAlpha($in){
		return $this->_storeFormIds[
			intval($in['_wpcf7'])
		]['post_type'] === CptAlpha::CPT_NAME;
	}
}