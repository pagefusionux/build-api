<?php

class japi {

  /**
   * @var string
   */
  private $api_uri;

  /**
   * @param string $api_uri
   */
  public function __construct($api_uri)
  {
    $this->api_uri = $api_uri;
  }

  /**
   * Returns a json object according to the jenkins uri segment passed.
   *
   * @param string $uri
   * @param array  $curlOptions
   *
   * @return string
   */
  public function execute($uri, array $curlOptions)
  {
    $url  = $this->api_uri . '/' . $uri;
    $curl = curl_init($url);
    curl_setopt_array($curl, $curlOptions);
    $ret = curl_exec($curl);

    $this->validateCurl($curl, sprintf('Error calling "%s"', $uri));

    return $ret;
  }

  /**
   * Validate curl_error() and http_code in a cURL request
   *
   * @param $curl
   * @param $errorMessage
   */
  private function validateCurl($curl, $errorMessage) {

    if (curl_errno($curl)) {
      throw new \RuntimeException($errorMessage);
    }
    $info = curl_getinfo($curl);

    if ($info['http_code'] === 403) {
      throw new \RuntimeException(sprintf('Access Denied [HTTP status code 403] to %s"', $info['url']));
    }
  }
}
