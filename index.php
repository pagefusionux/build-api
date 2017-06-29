<?php
/*
 * Queries Jenkins build server API to return build information.
 */

if (file_exists("config.php")) {
  require "config.php"; // jenkins api credentials

} else {
  // specify user credentials if config.php not available
  /*
  $api_user = "";
  $api_token = "";
  */
}

require "Bapi.php";
require "BapiError.php";

// Jenkins API URL
$api_url = "http://build.clearlink.com:8080";

// get the hostname of the requesting (source) app
if (isset($_GET["host"])) {
  $get_host = str_replace("www.", "", $_GET["host"]); // remove 'www.'
} else {
  $get_host = "localhost:3000"; // fallback to development host
}

// get option parameter
if (isset($_GET["option"])) { // options: status|status-commits
  $get_option = $_GET["option"];
} else {
  $get_option = "status"; // default
}

// get tree parameter
if (isset($_GET["tree"])) {
  $get_tree = $_GET["tree"];
} else {
  $get_tree = ""; // default
}

// get pretty parameter (if set to 1, Jenkins API output is displayed in a more readable format)
if (isset($_GET["pretty"])) {
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
