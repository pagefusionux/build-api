<?php
/*
 * Queries Jenkins build server API to return build information.
 */

require "config.php"; // jenkins api credentials
require "Bapi.php";
require "BapiError.php";

// API URL
$api_url = "http://build.clearlink.com:8080";

// get the hostname of the requesting (source) app
if (isset($_GET["host"])) {
  $get_host = str_replace("www.", "", $_GET["host"]); // remove 'www.'
} else {
  $get_host = "localhost:3000"; // fallback to development host
}

// get the requested option
if (isset($_GET["option"])) { // options: status|commits
  $get_option = $_GET["option"];
} else {
  $get_option = "status"; // default
}

// query the Jenkins server
$bapi = new Bapi($api_url, $get_host, $get_option, $api_user, $api_token);
$response = $bapi->execute();

// if no errors occurred, output response
if ($bapi->error) {
  echo $response;

} else { // output error
  echo $bapi->error_response;
}
