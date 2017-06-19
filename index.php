<?php
/*
 * Queries Jenkins build server API to return build information.
 */

// TODO: refactor to OO structure
//require "Bapi.php";
require "config.php"; // jenkins api credentials
require "host_map.php";
require "BapiError.php";

$error = 0;

// host
$api_uri = "http://build.clearlink.com:8080";

// set _GET parameters
if ($_GET["req_host"]) {
  $get_req_host = str_replace("www.", "", $_GET["req_host"]); // remove 'www.'
} else {
  $get_req_host = "localhost:3000"; // fallback to testing host
}

// get host override
if ($_GET["override_host"]) {
  $get_req_host = $_GET["override_host"];
}

$api_project = "";


// determine which host we're getting information for: get Jenkins v2 project name
try {
  // map host to project name
  foreach ($host_map as $key => $value) {
    if ($get_req_host == $key) {
      $api_project = $value;
    }
  }

  if (!$api_project) {
    throw new Exception("Requesting hostname (" . $get_req_host . ") failed to map to a Jenkins project.");
  }
} catch (Exception $ex) {

  $response_obj = new BapiError(BapiError::STATUS_UNPROCESSABLE_ENTITY, $ex->getMessage());

  //$response = '<pre>' . var_dump($response_obj, true) . '</pre>';
  $response = json_encode($response_obj);

  echo $response;
  $error = 1;
}

// determine which branch we're getting information for
if ($get_req_host == "localhost:3000" || (strpos(".dev.", $get_req_host) !== false)) {
  $api_branch = 'dev';
} elseif (strpos(".staging.", $get_req_host) !== false) {
  $api_branch = 'release';
} else {
  $api_branch = 'production';
}

// if no errors occurred, continue with cURL process
if (!$error) {

  // request parameters
  //$api_branch = "dev";
  //$api_tree = "result,timestamp,estimatedDuration,duration,url";
  $api_tree = "number,result,duration,timestamp,estimatedDuration";

  // build uri query
  $full_uri = $api_uri . "/job/" . $api_project . "/job/" . $api_branch . "/lastBuild/api/json?tree=" . $api_tree;

  // initialize cURL
  $curl = curl_init($full_uri);

  // set cURL options
  $headers = array(
    'Content-Type:application/json',
    'Authorization: Basic ' . base64_encode($api_user . ":" . $api_token)
  );
  curl_setopt($curl, \CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

  // execute request
  $response = curl_exec($curl);

  //$info = curl_getinfo($curl);

  //echo '<pre>' . var_export($info, true) . '</pre>';

  // output json response
  if ($response) {
    $response_obj = json_decode($response);

    // add our own information onto object
    $response_obj = (object) array_merge((array)$response_obj, array(
      'req_host' => $get_req_host,
      'api_project' => $api_project,
      'branch' => $api_branch
    ));
    //echo '<pre>' . var_export($response_obj, true) . '</pre>';

    echo json_encode($response_obj);

    /*
    $number = $response_obj["number"];
    $status = $response_obj["result"];
    $timestamp_start = $response_obj["timestamp"];
    $estimated_duration = $response_obj["estimatedDuration"];
    $duration = $response_obj["duration"];

    // convert javascript timestamp to unix timestamp
    $unix_timestamp = floor($timestamp_start / 1000);

    // convert unix timestamp to PHP date
    $php_datetime = date("Y-m-d H:i:s Z", $unix_timestamp);

    echo "
      build number: $number<br />
      status: $status<br />
      timestamp_start: $timestamp_start<br />
      estimated_duration: $estimated_duration<br />
      duration: $duration<br />
    ";
    */
  } else {
    echo curl_error($curl);
  }

  curl_close($curl);
}
