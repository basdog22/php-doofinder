<?php
/**
 * Author:: JoeZ99 (<jzarate@gmail.com>).
 *
 * License:: Apache License, Version 2.0
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may
 * not use this file except in compliance with the License. You may obtain
 * a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */
require_once dirname(__FILE__).'/errors.php';

class DoofinderManagementApi{

    const MANAGEMENT_DOMAIN_SUFFIX = '-api.doofinder.com';
    const MANAGEMENT_VERSION = 1;

    private $apiKey = null;
    private $clusterRegion = 'eu1';
    private $token = null;
    private $baseManagementUrl = null;

    function __construct($apiKey){
        $this->apiKey = $apiKey;
        $clusterToken = explode('-', $apiKey);
        $this->clusterRegion = $clusterToken[0];
        $this->token = $clusterToken[1];
        $this->baseManagementUrl = $this->clusterRegion."-".self::MANAGEMENT_DOMAIN_SUFFIX.
            "/v".self::MANAGEMENT_VERSION;
//        $this->baseManagementUrl = 'localhost:8000/api/v1';
    }

    function managementApiCall($method='GET', $entryPoint='', $params=null, $data=null){
        $headers = array('Authorization: Token '.$this->token,
                         'Content-Type: application/json',
                         'Expect:'); // Fixes the HTTP/1.1 417 Expectation Failed

        $fullUrl = $this->baseManagementUrl.'/'.$entryPoint;
        $session = curl_init($fullUrl);
        curl_setopt($session, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($session, CURLOPT_HEADER, false);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true); // Tell curl to return the response
        curl_setopt($session, CURLOPT_HTTPHEADER, $headers); // Adding request headers
        $response = curl_exec($session);
        $httpCode = curl_getinfo($session, CURLINFO_HTTP_CODE);
        curl_close($session);

        handleErrors($httpCode, $response);

        return json_decode($response, true);

    }

    function show() {
        echo $this->baseManagementUrl;
        echo "\n";
        echo $this->token;
        echo "\n";
        echo $this->baseManagementUrl;
        $a = 'a';
    }



}