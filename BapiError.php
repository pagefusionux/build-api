<?php

/**
 * Handles exceptions/JSON response errors
 */
class BapiError {
  const STATUS_INTERNAL_SERVER_ERROR = 500;
  const STATUS_UNPROCESSABLE_ENTITY = 422;

  public $status;
  public $messages;

  function __construct($status, $message = null) {
    $this->status = $status;

    if (isset($message)) {
      $this->messages = array(
        'errors' => array($message)
      );
    } else {
      $this->messages = array();
    }
  }

  public function addMessage($key, $message) {
    if (!isset($message)) {
      $message = $key;
      $key = 'errors';
    }

    if (!isset($this->messages[$key])) {
      $this->messages[$key] = array();
    }

    $this->messages[$key][] = $message;
  }

  public function getMessages() {
    return $this->messages;
  }

  public function getStatus() {
    return $this->status;
  }

}
