<?php

namespace coc\core;

use coc\ClockOfChange;
use coc\shortcodes\ShUserwall;
use Exception;
use Requests;

class CoCAPI
{
    const ENDPOINT_GET_COUNT                      = 'cube.php';
    const ENDPOINT_ENTRIES                        = '/entries';
    const ENDPOINT_ENTRIES_TOGGLE                 = '/entries/toggle';
    const ENDPOINT_ENTRIES_TOGGLE_EMAIL_CONFIRMED = '/entries/toggle-email-confirmed';
    const ENDPOINT_COUNTRIES                      = '/countries';
    const ENDPOINT_DELETE_ENTRY                   = '/delete';
    const ENDPOINT_DELETE_IMAGE                   = '/deleteImage';
    const ENDPOINT_ROTATE_IMAGE                   = '/rotateImage';

    private $_apiKey = null;
    private $_baseUrl = null;
    private $_validUrl = 'https://tools.human-connection.org';

    /**
     * @var string - name of the upload folder for images
     */
    private $_uploadFolder = 'avataaars/';

    /**
     * @var Translation
     */
    private $translation;

    /**
     * CoCAPI constructor.
     *
     * @param Translation $translation
     */
    public function __construct($translation)
    {
        $this->translation = $translation;

        if ($this->_apiKey === null) {
            $apiKey = ClockOfChange::app()->optionsManager()->getOption(OptionsManager::OPT_API_KEY);
            if ($apiKey !== false) {
                $this->_apiKey = $apiKey;
            }
        }

        if ($this->_baseUrl === null) {
            $baseUrl = ClockOfChange::app()->optionsManager()->getOption(OptionsManager::OPT_API_URL);
            if ($baseUrl !== false) {
                $this->_baseUrl = $baseUrl;
            }
        }
    }

    public function init()
    {
        add_action('rest_api_init', [$this, 'initCustomRoutes']);
    }

    public function initCustomRoutes()
    {
        register_rest_route(
            'coc/v2', '/createEntry/', [
                'methods'  => 'POST',
                'callback' => [$this, 'saveEntry'],
            ]
        );

        register_rest_route(
            'coc/v2', '/getEntries/', [
                'methods'  => 'GET',
                'callback' => [$this, 'loadMore'],
            ]
        );

        register_rest_route(
            'coc/v2', '/deleteEntry/', [
                'methods'  => 'GET',
                'callback' => [$this, 'deleteEntry'],
            ]
        );

        register_rest_route(
            'coc/v2', '/deleteImage/', [
                'methods'  => 'GET',
                'callback' => [$this, 'deleteImage'],
            ]
        );

        register_rest_route(
            'coc/v2', '/rotateImage/', [
                'methods'  => 'GET',
                'callback' => [$this, 'rotateImage'],
            ]
        );

        register_rest_route(
            'coc/v2', '/getCount/', [
                'methods'  => 'GET',
                'callback' => [$this, 'getCount'],
            ]
        );
    }

    public function loadMore()
    {
        $offset               = (int) ($_GET['offset'] ?? 0);
        $filterByProfileImage = $_GET['profileImage'] == 1 ? 1 : 0;
        $filterByCountry      = (string) ($_GET['country'] ?? '');

        $filter = [
            'active'       => true,
            'profileImage' => $filterByProfileImage,
            'country'      => $filterByCountry,
        ];

        $users = $this->getUsers(
            $offset * ShUserwall::PAGE_SIZE,
            $filter,
            $_GET['orderByDate'] ? 'id' : null,
            $_GET['orderByDate'] ? $_GET['orderByDate'] : null
        );
        $out   = [];

        if (isset($users->success) && isset($users->results) && !empty($users->results)) {
            foreach ($users->results as $user) {
                $obj            = new \stdClass();
                $obj->firstname = $user->firstname . ' ' . $user->lastname;
                $obj->message   = $user->message;
                $obj->image     = $user->image;
                $obj->anon      = $user->anon;
                $obj->country   = $user->country;

                $out[] = $obj;
            }
        }

        return $out;
    }

    public function toggleStatus($entryId, $action)
    {
        $aMap = ['cocactivate' => 1, 'cocdisable' => 2];
        $ch   = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['API-Key: ' . $this->_apiKey]);
        curl_setopt($ch, CURLOPT_URL, $this->_baseUrl . self::ENDPOINT_ENTRIES_TOGGLE);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, ['id' => $entryId, 'state' => $aMap[$action]]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $resp = curl_exec($ch);
        curl_close($ch);

        return json_decode($resp);
    }

    public function toggleEmailConfirmed($entryId, $action)
    {
        $aMap = ['cocemailactivate' => 1, 'cocemaildisable' => 0];
        $ch   = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['API-Key: ' . $this->_apiKey]);
        curl_setopt($ch, CURLOPT_URL, $this->_baseUrl . self::ENDPOINT_ENTRIES_TOGGLE_EMAIL_CONFIRMED);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, ['id' => $entryId, 'state' => $aMap[$action]]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $resp = curl_exec($ch);
        curl_close($ch);

        return json_decode($resp);
    }

    public function saveEntry($data)
    {
        $params   = $data->get_params();
        $response = [];

        if (!isset($params['email']) || empty($params['email'])) {
            $response['email'] = $this->translation->t('errorMissingRequiredField', 'Missing required field');
        }

        if (!isset($params['country']) || empty($params['country']) || $params['country'] === 'null') {
            $response['country'] = $this->translation->t('errorMissingRequiredField', 'Missing required field');
        }

        if (!isset($params['firstname']) || empty($params['firstname'])) {
            $response['firstname'] = $this->translation->t('errorMissingRequiredField', 'Missing required field');
        }

        if (!isset($params['pr']) || $params['pr'] !== 'true') {
            $response['pr'] = $this->translation->t('errorMissingRequiredField', 'Missing required field');
        }

        if (!isset($params['age']) || $params['age'] !== 'true') {
            $response['age'] = $this->translation->t('errorMissingRequiredField', 'Missing required field');
        }

        // ensure required fields
        // form can only be send when pr is set while we assume that any record showing up on node has accepted privacy
        if (!empty($response)) {
            return array_merge(['success' => false], $response);
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
            'pax'        => $params['pax'] == '1' ? 1 : 0,
        ];

        // avatar from gen
        if (isset($params['imageUrl']) && strpos($params['imageUrl'], $this->_validUrl) !== false) {
            add_filter('upload_dir', [$this, 'setTempUploadDir']);
            $uploadFQN = $this->_ensureUploadFolder(wp_upload_dir()['path']);
            if ($uploadFQN != false) {
                $fileUrlMd5 = md5($params['imageUrl']);
                $FQN        = $uploadFQN . $fileUrlMd5 . '.png';
                if (!file_exists($FQN)) {
                    $file = file_get_contents($params['imageUrl']);
                    file_put_contents($FQN, $file);
                }

                $entryData['image'] = curl_file_create($FQN);
            }
            remove_filter('upload_dir', [$this, 'setTempUploadDir']);
        } else {
            // custom image thingy
            if (!isset($params['image'])) {
                $image = $data->get_file_params();
                if (!empty($image)) {
                    $tmpFile = $image['image']['tmp_name'];
                    $fType   = $image['image']['type'];
                    $fName   = $image['image']['name'];

                    $entryData['image'] = curl_file_create($tmpFile, $fType, $fName);
                } else {
                    $entryData['image'] = '';
                }
            } else {
                if ($params['image'] === 'undefined') {
                    $entryData['image'] = curl_file_create(
                        ClockOfChange::$pluginAssetsPath . '/images/coc-placeholder.jpg'
                    );
                } else {
                    $imgPath            = str_replace(
                        get_bloginfo('url') . '/wp-content/plugins/coc', "", $params['image']
                    );
                    $entryData['image'] = curl_file_create(dirname(__FILE__) . '/../..' . $imgPath);
                }
            }
        }

        return $this->sendToCoCApi($entryData);
    }

    public function setTempUploadDir($dir)
    {
        return [
                'path'   => $dir['basedir'] . '/' . $this->_uploadFolder,
                'url'    => $dir['baseurl'] . '/' . $this->_uploadFolder,
                'subdir' => '/' . $this->_uploadFolder,
            ] + $dir;
    }

    private function _ensureUploadFolder($path)
    {
        if ($path !== null && $path !== '') {
            if (!file_exists($path) && !is_dir($path)) {
                mkdir($path, 0770);
            }

            return $path;
        }

        return false;
    }

    protected function sendToCoCApi($entryData)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['API-Key: ' . $this->_apiKey]);
        curl_setopt($ch, CURLOPT_URL, $this->_baseUrl . self::ENDPOINT_ENTRIES);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $entryData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $resp = curl_exec($ch);
        curl_close($ch);

        return json_decode($resp);
    }

    public function getUsers($offset = 0, $filter = [], $orderBy = null, $order = null)
    {
        if (!isset($filter['active'])) {
            $filter['active'] = true;
        }
        if (!isset($filter['profileImage'])) {
            $filter['profileImage'] = false;
        }
        if (!isset($filter['confirmed'])) {
            $filter['confirmed'] = 'all';
        }
        if (!isset($filter['status'])) {
            $filter['status'] = 'all';
        }
        if (!isset($filter['country'])) {
            $filter['country'] = '';
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['API-Key: ' . $this->_apiKey]);
        curl_setopt(
            $ch, CURLOPT_URL,
            $this->_baseUrl . self::ENDPOINT_ENTRIES
            . '?isActive=' . (int) $filter['active']
            . '&limit=' . ShUserwall::PAGE_SIZE
            . '&offset=' . (int) $offset
            . '&orderBy=' . (string) $orderBy
            . '&order=' . (string) $order
            . '&profileImage=' . (int) $filter['profileImage']
            . '&confirmed=' . (string) $filter['confirmed']
            . '&status=' . (string) $filter['status']
            . '&country=' . (string) $filter['country']
        );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $resp = curl_exec($ch);
        curl_close($ch);

        return json_decode($resp);
    }

    public function getCount($filter = [])
    {
        if (!isset($filter['active'])) {
            $filter['active'] = true;
        }
        if (!isset($filter['profileImage'])) {
            $filter['profileImage'] = false;
        }
        if (!isset($filter['confirmed'])) {
            $filter['confirmed'] = 'all';
        }
        if (!isset($filter['status'])) {
            $filter['status'] = 'all';
        }

        $url = $this->_baseUrl . '/' . self::ENDPOINT_GET_COUNT
            . '?isActive=' . (int) $filter['active']
            . '&profileImage=' . (int) $filter['profileImage']
            . '&confirmed=' . (string) $filter['confirmed']
            . '&status=' . (string) $filter['status'];

        try {
            $response = Requests::get($url);
            if ($response->status_code === 200 && $response->success === true) {
                return $response->body;
            }
        } catch (Exception $e) {
            return 0;
        }
    }

    public function getCountries()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['API-Key: ' . $this->_apiKey]);
        curl_setopt(
            $ch, CURLOPT_URL,
            $this->_baseUrl . self::ENDPOINT_COUNTRIES
        );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $resp             = curl_exec($ch);
        $httpResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $response = json_decode($resp);

        if ($httpResponseCode === 200 && $response->success === true) {
            return $response->countries;
        }

        return null;
    }

    public function deleteEntry($id)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['API-Key: ' . $this->_apiKey]);
        curl_setopt(
            $ch, CURLOPT_URL,
            $this->_baseUrl . self::ENDPOINT_DELETE_ENTRY . '/' . esc_attr($id)
        );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response->status_code === 200 && $response->success === true) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteImage($id)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['API-Key: ' . $this->_apiKey]);
        curl_setopt(
            $ch, CURLOPT_URL,
            $this->_baseUrl . self::ENDPOINT_DELETE_IMAGE . '/' . esc_attr($id)
        );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response->status_code === 200 && $response->success === true) {
            return true;
        } else {
            return false;
        }
    }

    public function rotateImage($id, $degree)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['API-Key: ' . $this->_apiKey]);
        curl_setopt(
            $ch, CURLOPT_URL,
            $this->_baseUrl . self::ENDPOINT_ROTATE_IMAGE . '/' . esc_attr($id) . '/' . esc_attr($degree)
        );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response->status_code === 200 && $response->success === true) {
            return true;
        } else {
            return false;
        }
    }
}
