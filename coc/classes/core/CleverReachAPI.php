<?php
/*
 * @copyright Copyright 2021 | Human Connection gemeinnützige GmbH - Alle Rechte vorbehalten.
 * @author    Matthias Böhm <mail@matthiasboehm.com>
 */

namespace coc\core;


use coc\ClockOfChange;

class CleverReachAPI
{
    /**
     * @var string
     */
    const BASE_URL = 'https://rest.cleverreach.com';

    /**
     * @var string
     */
    const ENDPOINT_REFRESH_ACCESS_TOKEN = '/oauth/token.php';

    /**
     * @var string
     */
    const ENDPOINT_CREATE_RECEIVER = '/v3/groups.json/%d/receivers';

    /**
     * @var OptionsManager
     */
    private $optionsManager;

    /**
     * @var string
     */
    private $clientId;

    /**
     * @var string
     */
    private $clientSecret;

    /**
     * @var string
     */
    private $refreshToken;

    /**
     * @var string
     */
    private $accessToken;

    /**
     * @var string
     */
    private $groupId;

    /**
     * @var Translation
     */
    public function __construct()
    {
        $this->optionsManager = ClockOfChange::app()->optionsManager();
        $this->clientId       = $this->optionsManager->getOption(OptionsManager::OPT_CLEVERREACH_CLIENT_ID);
        $this->clientSecret   = $this->optionsManager->getOption(OptionsManager::OPT_CLEVERREACH_CLIENT_SECRET);
        $this->refreshToken    = $this->optionsManager->getOption(OptionsManager::OPT_CLEVERREACH_REFRESH_TOKEN);
        $this->accessToken    = $this->optionsManager->getOption(OptionsManager::OPT_CLEVERREACH_ACCESS_TOKEN);
        $this->groupId        = $this->optionsManager->getOption(OptionsManager::OPT_CLEVERREACH_GROUP_ID);
    }

    /**
     * @param string $email
     * @param string $firstname
     * @param string $lastname
     */
    public function addSubscriber($email, $firstname, $lastname)
    {
        $this->refreshAccessToken();

        $url = sprintf(self::BASE_URL . self::ENDPOINT_CREATE_RECEIVER, $this->groupId);

        $data = [
            'email'             => $email,
            'activated'         => time(),
            'registered'        => time(),
            'deactivated'       => '0',
            'source'            => 'HC Clock',
            'global_attributes' => [
                'firstname' => $firstname,
                'lastname'  => $lastname,
            ],
        ];

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->accessToken,
            'Content-Type:application/json'
        ]);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);
        curl_close($curl);
    }

    /**
     * Refresh the Access Token
     */
    private function refreshAccessToken()
    {
        $authorization = base64_encode($this->clientId . ":" . $this->clientSecret);

        $curl = curl_init();
        curl_setopt($curl,CURLOPT_URL, self::BASE_URL . self::ENDPOINT_REFRESH_ACCESS_TOKEN);
        curl_setopt($curl,CURLOPT_POSTFIELDS, array("grant_type" => "refresh_token", "refresh_token" => $this->refreshToken));
        curl_setopt($curl,CURLOPT_HTTPHEADER, array("Authorization: Basic ".$authorization));
        curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($curl);
        $httpResponseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close ($curl);

        // The final $result contains the new access_token and some other information.
        // For you to see it, we dump it out here.
        if ($httpResponseCode === 200 && $result && !property_exists($result,'error') && $result->access_token && $result->refresh_token) {
            update_option(OptionsManager::OPT_CLEVERREACH_ACCESS_TOKEN, $result->access_token);
            update_option(OptionsManager::OPT_CLEVERREACH_REFRESH_TOKEN, $result->refresh_token);
        }
    }
}
