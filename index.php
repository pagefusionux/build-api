<?php
/*
 * Queries Jenkins build server API to return build information.
 */

require "config.php"; // jenkins api credentials
require "Bapi.php";
require "BapiError.php";

// specify user credentials if config.php not available
/*
$api_user = "";
$api_token = "";
*/

// API URL
$api_url = "http://build.clearlink.com:8080";

// get the hostname of the requesting (source) app
if (isset($_GET["host"])) {
  $get_host = str_replace("www.", "", $_GET["host"]); // remove 'www.'
} else {
  $get_host = "localhost:3000"; // fallback to development host
}

// get option parameter
if (isset($_GET["option"])) { // options: status|commits
  $get_option = $_GET["option"];
} else {
  $get_option = "status"; // default
}

// get tree parameter
if (isset($_GET["tree"])) { // options: status|commits
  $get_tree = $_GET["tree"];
} else {
  $get_tree = ""; // default
}

// get pretty parameter
if (isset($_GET["pretty"])) { // options: status|commits
  $get_pretty = $_GET["pretty"];
} else {
  $get_pretty = 0; // default
}

// query the Jenkins server
$bapi = new Bapi($api_url, $get_host, $get_option, $get_pretty, $get_tree, $api_user, $api_token);
$response = $bapi->execute();

// if no errors occurred, output response
if (!$bapi->error) {
  echo $response;

} else { // output error
  echo $bapi->error_response;
}
