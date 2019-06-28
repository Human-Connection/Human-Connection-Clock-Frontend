<?php
namespace coc\core;

use coc\ClockOfChange;
use coc\shortcodes\ShUserwall;
use \Requests;

class CoCAPI
{
	const END_GET_COUNT = 'cube.php';
	const END_ENTRIES   = '/entries';
	const END_ENTRIES_TOGGLE = '/entries/toggle';

	private $_apiKey  = null;
	private $_baseUrl = null;
	private $_validUrl = 'https://tools.human-connection.org';

	/**
	 * @var string - name of the upload folder for images
	 */
	private $_uploadFolder = 'avataaars/';

	public function __construct(){
		if($this->_apiKey === null){
			$apiKey = ClockOfChange::app()->optionsManager()->getOption(OptionsManager::OPT_API_KEY);
			if($apiKey !== false){
				$this->_apiKey = $apiKey;
			}
		}

		if($this->_baseUrl === null){
			$baseUrl = ClockOfChange::app()->optionsManager()->getOption(OptionsManager::OPT_API_URL);
			if($baseUrl !== false){
				$this->_baseUrl = $baseUrl;
			}
		}
	}

	public function init(){
		add_action('rest_api_init', [$this, 'initCustomRoutes']);
	}

	public function initCustomRoutes() {
		register_rest_route( 'coc/v2', '/createEntry/', [
			'methods' => 'POST',
			'callback' => [$this, 'saveEntry']
		]);

		register_rest_route( 'coc/v2', '/getEntries/', [
			'methods' => 'GET',
			'callback' => [$this, 'loadMore']
		]);
	}

	public function loadMore(){
		$offset = (int) $_GET['offset'] ?? 0;
		$orderByDate = $_GET['orderByDate'] === 'asc' ? 'asc' : 'desc';
		$filterByProfileImage = (int) $_GET['profileImage'] === 1 ? 1 : 0;

		$users = $this->getUsers(
		    $offset * ShUserwall::PAGE_SIZE,
            true,
            $orderByDate,
            $filterByProfileImage
        );
		$out = [];
		if(isset($users->success) && isset($users->results) && !empty($users->results)){
			foreach($users->results as $user){
				$obj            = new \stdClass();
				$obj->firstname = $user->firstname.' '.$user->lastname;
				$obj->message   = $user->message;
				$obj->image     = $user->image;
				$obj->anon      = $user->anon;

				$out[] = $obj;
			}
		}
		return $out;
	}

	public function toggleStatus($entryId, $action){
		$aMap = ['cocactivate' => 1, 'cocdisable' => 2];
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPHEADER, ['API-Key: '.$this->_apiKey]);
		curl_setopt($ch, CURLOPT_URL, $this->_baseUrl.self::END_ENTRIES_TOGGLE);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, ['id'=>$entryId, 'state'=>$aMap[$action]]);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$resp = curl_exec($ch);
		curl_close ($ch);

		return json_decode($resp);
	}

	public function saveEntry($data){
		$params = $data->get_params();

		// ensure required fields
		// form can only be send when pr is set while we assume that any record showing up on node has accepted privacy
		if(!isset($params['email']) || !isset($params['message']) ||
		   $params['email'] === '' || $params['message'] === '' ||!isset($params['pr']) || $params['pr'] !== 'true'){
			return ['success'=>false, 'error'=>'missing required fields.'];
		}

		$entryData = [
			'firstname'  => $params['firstname'],
			'lastname'   => $params['lastname'],
			'email'      => $params['email'],
			'message'    => $params['message'],
			'country'    => $params['country'],
			'anon'       => $params['anon'] === 'true' ? 1 : 0,
			'beta'       => $params['beta'] === 'true' ? 1 : 0,
			'newsletter' => $params['nl'] === 'true' ? 1 : 0,
			'pax' 		 => $params['pax'] == '1' ? 1 : 0
		];

		// avatar from gen
		if(isset($params['imageUrl']) && strpos($params['imageUrl'], $this->_validUrl) !== false){
			add_filter('upload_dir', [$this, 'setTempUploadDir']);
			$uploadFQN = $this->_ensureUploadFolder(wp_upload_dir()['path']);
			if($uploadFQN != false) {
				$fileUrlMd5 = md5($params['imageUrl']);
				$FQN = $uploadFQN.$fileUrlMd5.'.png';
				if(!file_exists($FQN)){
					$file = file_get_contents($params['imageUrl']);
					file_put_contents($FQN, $file);
				}

				$entryData['image'] = curl_file_create($FQN);
			}
			remove_filter('upload_dir', [$this, 'setTempUploadDir']);
		}else{
			// custom image thingy
			if(!isset($params['image'])){
				$image   = $data->get_file_params();
				if(!empty($image)){
					$tmpFile = $image['image']['tmp_name'];
					$fType   = $image['image']['type'];
					$fName   = $image['image']['name'];

					$entryData['image'] = curl_file_create($tmpFile, $fType, $fName);
				}else{
					$entryData['image'] = '';
				}
			}else{
				if($params['image'] === 'undefined'){
					$entryData['image'] = curl_file_create(ClockOfChange::$pluginAssetsPath.'/images/coc-placeholder.jpg');
				}else{
					$imgPath = str_replace(get_bloginfo('url').'/wp-content/plugins/coc', "", $params['image']);
					$entryData['image'] = curl_file_create(dirname(__FILE__).'/../..'.$imgPath);
				}
			}
		}

		return $this->sendToCoCApi($entryData);
	}

	public function setTempUploadDir($dir){
		return [
	        'path'   => $dir['basedir'] . '/' . $this->_uploadFolder,
	        'url'    => $dir['baseurl'] . '/' . $this->_uploadFolder,
	        'subdir' => '/' . $this->_uploadFolder,
        ] + $dir;
	}

	private function _ensureUploadFolder($path){
		if($path !== null && $path !== ''){
			if(!file_exists($path) && !is_dir($path)){
				mkdir($path, 0770);
			}

			return $path;
		}

		return false;
	}

	protected function sendToCoCApi($entryData){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPHEADER, ['API-Key: '.$this->_apiKey]);
		curl_setopt($ch, CURLOPT_URL, $this->_baseUrl.self::END_ENTRIES);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $entryData);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$resp = curl_exec($ch);
		curl_close ($ch);

		return json_decode($resp);
	}

	public function getUsers($offset = 0, $active = true, $orderByDate = 'desc', $profileImage = 0){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPHEADER, ['API-Key: '.$this->_apiKey]);
		curl_setopt($ch, CURLOPT_URL,
            $this->_baseUrl.self::END_ENTRIES
            . '?isActive=' . (int) $active
            . '&limit=' . ShUserwall::PAGE_SIZE
            . '&offset=' . (int) $offset
            . '&orderByDate=' . $orderByDate
            . '&profileImage=' . (int) $profileImage
        );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$resp = curl_exec($ch);
		curl_close ($ch);

		return json_decode($resp);
	}

	public function getCount(){
		$url = $this->_baseUrl.'/'.self::END_GET_COUNT;
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
