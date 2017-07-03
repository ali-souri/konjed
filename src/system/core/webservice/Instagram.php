<?php

namespace Core\WebService;

require_once __DIR__ . '/../Loader.php';
\Loader::load("all");

class Instagram {

    //public $code = "";
    
    function __construct(){}

    public function OAuthStep1($client_id, $redirect_url) {

        header("Location: https://api.instagram.com/oauth/authorize/?client_id"
                . "=" . $client_id . "&redirect_uri=" . $redirect_url . "&response_type=code");
        exit();
    }

    public function OAuthStep3($client_id, $redirect_url, $client_pass, $code) {
        // echo 'salam1';
        $webservice = new WebServiceAPI();
        $res = $webservice->cURL("POST", "https://api.instagram.com/oauth/access_token", [
            "client_id" => $client_id,
            "client_secret" => $client_pass,
            "code" => $code,
            "grant_type" => "authorization_code",
            "redirect_uri" => $redirect_url
        ]);
        return $res;
    }

    public function GetOwnMedia($ACCESS_TOKEN,$COUNT ,$MIN_ID , $MAX_ID){
        $webservice = new WebServiceAPI();
        $res = $webservice->cURL("GET", "https://api.instagram.com/v1/users/self/media/recent/?access_token=$ACCESS_TOKEN&COUNT=$COUNT&MIN_ID=$MIN_ID&MAX_ID=$MAX_ID", null);
        return $res;
    }

    public function GetHashTagCount($ACCESS_TOKEN,$tag_name){
        $webservice = new WebServiceAPI();
        $res = $webservice->cURL("GET", "https://api.instagram.com/v1/tags/$tag_name?access_token=$ACCESS_TOKEN", null);
        return $res;
    }

     public function GetHashTagMedia($ACCESS_TOKEN,$COUNT ,$MIN_ID , $MAX_ID , $tag_name){
        $webservice = new WebServiceAPI();
        $res = $webservice->cURL("GET", "https://api.instagram.com/v1/tags/$tag_name/media/recent/?access_token=$ACCESS_TOKEN&COUNT=$COUNT&MIN_TAG_ID=$MIN_ID&MAX_TAG_ID=$MAX_ID", null);
        return $res;
    }

}

?>
