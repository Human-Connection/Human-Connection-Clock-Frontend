<?php
namespace coc\core;

use \Requests;

class AvatarAPI
{
	const END_OPTIONS = 'avataaars/options';

	private $_baseURL = 'https://tools.human-connection.org/index.php/';

	public function __construct(){}

	public function getOptions(){
		$url = $this->_baseURL.self::END_OPTIONS;
		try {
			$response = Requests::get($url);
			if($response->status_code === 200 && $response->success === true){
				return $response->body;
			}
		}catch(\Exception $e){
			return 5000;
		}
	}
}