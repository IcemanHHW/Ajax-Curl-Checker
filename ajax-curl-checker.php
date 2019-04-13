<?php

/**
 * @package AjaxCurlChecker

 */
/*
Plugin Name: Ajax Curl Checker
Description: This plugin contains a curl request to a Json response
Version: 1.0.0
Author: Maarten Kampmeijer
Author URI: https://github.com/IcemanHHW/
License: GPLv2 or later
Text Domain: ajax-curl-checker
*/

/*
Ajax Curl Checker is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 2 of the License, or
any later version.

Ajax Curl Checker is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with Ajax Curl Checker.
*/

defined( 'ABSPATH' ) or die( 'This is a very secure plugin!' );

class Ajax_Url_Checker {

    function __construct()
    {
        add_action( 'init', array( $this, 'auc_test' ) );
    }

    function auc_test() {
        $current_url = get_option('site_url') . $_SERVER['REQUEST_URI'];
        if ( strpos($current_url, '/test_curl') !== false) {

            $postcode = $_GET["postcode"];
            $number = $_GET["number"];

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, "https://api.nextpertise.nl/broadband/basic/v1");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "{\n  \"jsonrpc\": \"2.0\",\n  \"id\": 1,\n  \"method\": \"zipcode\",\n  \"params\": {\n    \"zipcode\": \"$postcode\",\n    \"housenr\": $number\n  }\n}");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_USERPWD, "gebruikersnaam" . ":" . "wachtwoord");

            $headers = array();
            $headers[] = "Content-Type: application/x-www-form-urlencoded";
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $result = curl_exec($ch);
            if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
            }

            header('Content-Type: application/json');
            echo json_encode($result);

            curl_close ($ch);
            exit();;
        }
    }
}

new Ajax_Url_Checker()

?>
