<?php

class Bapi {

  /**
   * @var string
   */
  public $error;
  public $error_response;
  public $api_url;
  public $api_user;
  public $api_token;
  public $get_host;
  public $get_option;
  public $get_pretty;
  public $get_tree;
  public $api_project;
  //public $api_branch;

  /**
   * @param string $api_uri
   */
  public function __construct($api_url, $get_host, $get_option, $get_pretty, $get_tree, $api_user, $api_token) {
    $this->error = 0;
    $this->error_reponse = 0;
    $this->api_url = $api_url;
    $this->api_user = $api_user;
    $this->api_token = $api_token;
    $this->get_host = $get_host;
    $this->get_option = $get_option;
    $this->get_pretty = $get_pretty;
    $this->get_tree = $get_tree;
    $this->api_project = $this->getProject();
    //$this->api_branch = $this->getBranch();
  }

  /**
   * Takes the $host_map associative array and maps the project name (value) according to
   * the url segment that matches the key.
   *
   * @return string
   */
  public function getProject() {
    $api_project = "";

    $host_map = array(
      // testing/development
      "localhost:3000" => "v2-cuttlefish.clearlink",
      "localhost.buildstatus" => "v2-cuttlefish.clearlink",

      // internal (clearlink)
      "cuttlefish" => "v2-cuttlefish.clearlink",

      // telco1
      "attsavings" => "v2-attsavings",
      "attexperts" => "v2-attexperts",

      // frontier
      "west.frontier" => "v2-west.frontier",

      // biz
      "business.frontier" => "v2-business.frontier",

      // energy
      "amigoenergy" => "v2-amigoenergy",
      "amigoenergyplans" => "v2-amigoenergyplans",
      "justenergy" => "v2-justenergy",
      "taraenergy" => "v2-taraenergy",

      // sat/sec
      "centurylinkquote" => "v2-centurylinkquote",
      "getcenturylink" => "v2-getcenturylink",
      "vivintsource" => "v2-vivintsource",
      "satelliteinternet" => "v2-satelliteinternet",
      "usdirect" => "v2-usdirect"
    );

    try {
      // map host to project name
      foreach ($host_map as $key => $value) {
        //if ($get_host == $key) {
        if (strpos($this->get_host, $key) !== false) {
          $api_project = $value;
        }
      }

      if (!$api_project) {
        throw new Exception("Requesting hostname (" . $this->get_host . ") failed to map to a Jenkins project.");
      } else {
        return $api_project;
      }
    } catch (Exception $ex) {

      $response_obj = new BapiError(BapiError::STATUS_UNPROCESSABLE_ENTITY, $ex->getMessage());

      //$response = '<pre>' . var_dump($response_obj, true) . '</pre>';
      $response = json_encode($response_obj);

      $this->error = 1;
      $this->error_response = $response;

      return 0;
    }
  }

  /**
   * Takes the passed hostname and returns the branch.
   *
   * @return string
   */
  /*
  public function getBranch() {
    if ($this->get_host == "localhost:3000" || $this->get_host == "localhost.buildstatus" || preg_match("/\.dev\./", $this->get_host)) {
      $api_branch = 'dev';
    } elseif (preg_match("/\.staging\./", $this->get_host)) {
      $api_branch = 'release';
    } else {
      $api_branch = 'production';
    }

    return $api_branch;
  }
  */

  /**
   * Executes a cURL request to the Jenkins build server which returns a json object
   * according to the jenkins 'path', and 'tree' parameters passed.
   *
   * @return json string
   */
  public function execute() {

    if ($this->get_option == "status-commits") { // status and commit info
      // affectedPaths[*]
      $api_tree = "jobs[name,lastBuild[number,duration,timestamp,result,estimatedDuration,changeSets[items[date,commitId,msg,author[fullName],authorEmail,paths[*]]]]]";

    } elseif ($this->get_tree) { // tree parameter passed over URL
      $api_tree = $this->get_tree;

    } else { // $this->get_option == "status" (default)
      $api_tree = "jobs[name,lastBuild[number,duration,timestamp,result,estimatedDuration]]";
    }

    //$full_req = $this->api_url . "/job/" . $this->api_project . "/job/" . $this->api_branch . "/lastBuild/api/json?tree=" . $api_tree;
    $full_req = $this->api_url . "/job/" . $this->api_project . "/api/json?tree=" . $api_tree . "&pretty=true";

    // initialize cURL
    $curl = curl_init($full_req);

    // set cURL options
    $headers = array(
      'Content-Type:application/json',
      'Authorization: Basic ' . base64_encode($this->api_user . ":" . $this->api_token)
    );
    curl_setopt($curl, \CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    // execute request
    $response = curl_exec($curl);

    // output json response
    if ($response) {
      $response_obj = json_decode($response, true); // decode into associative array

      // filter; only output the info for the branches we want
      $jobs_arr = $response_obj["jobs"];
      $jobs_we_want = array('dev', 'hotfix', 'release', 'production'); // git branch names
      $new_jobs_arr = array();

      for ($i = 0; $i < count($jobs_arr); $i++) {
        if (in_array($jobs_arr[$i]["name"], $jobs_we_want)) {
          $job_name = $jobs_arr[$i]["name"];

          unset($jobs_arr[$i]["name"]);

          // remove _class properties
          unset($jobs_arr[$i]["_class"]);
          unset($jobs_arr[$i]["lastBuild"]["_class"]);

          if ($this->get_option == "status-commits") {
            for ($j = 0; $j < count($jobs_arr[$i]["lastBuild"]["changeSets"]); $j++) {
              unset($jobs_arr[$i]["lastBuild"]["changeSets"][$j]["_class"]);

              for ($k = 0; $k < count($jobs_arr[$i]["lastBuild"]["changeSets"][$j]["items"]); $k++) {
                unset($jobs_arr[$i]["lastBuild"]["changeSets"][$j]["items"][$k]["_class"]);
              }
            }
          }
          $new_jobs_arr[$job_name] = $jobs_arr[$i]; // re-organize stuff under 'name' of branch (for ease-of-use)
        }
      }

      // re-organize under 'branches' key (for ease-of-use)
      $branches_arr["branches"] = $new_jobs_arr;

      // merge some other information onto array and typecast to object
      $new_response_obj = (object)array_merge(array(
        'host' => $this->get_host,
        'project' => $this->api_project
      ), $branches_arr);

      if ($this->get_pretty) {
        echo '<pre>' . var_export($new_response_obj, true) . '</pre>';
      } else {
        echo json_encode($new_response_obj);
      }

    } else {
      echo curl_error($curl);
    }

    curl_close($curl);
  }
}
