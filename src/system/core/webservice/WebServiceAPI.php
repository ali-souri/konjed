<?php

namespace Core\WebService;

/**
 * --- This WebServiceAPI contains the base module of webservices based on CURl
 * @author Ali Souri ap.alisouri@gmail.com
 * @package Core\WebService
 */
class WebServiceAPI {
    
    function __construct(){}

    public function cURL($type, $url, $params) {
        $curl = curl_init();

        switch ($type) {
            case "POST":
                curl_setopt_array($curl, [
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_URL => $url,
                    CURLOPT_POST => 1,
                    CURLOPT_POSTFIELDS => $params
                        ]
                );
                break;
            case "GET":
                curl_setopt_array($curl, [
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_URL => $url
                ]);
                break;
            default: return 0;
        }
        $curl_response = curl_exec($curl);
        curl_close($curl);
        return $curl_response;
    }

}

?>