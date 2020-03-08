<?php

namespace Curl;

use Exception;

class YTSearch extends Curl
{
  public $apikey = 'AIzaSyDlna9xQsXvsCK5oUKAsYozuk5YHczAyS0';
  public $keyword = 'remix slow';
  private static $_instance = null;
  private $yts;
  private $response_data = [
    'error' => true,
  ];
  private $query = [
    'part' => 'id,snippet',
    'q' => null,
    'maxResults' => 10,
    'key' => null,
  ];
  private $expyt = 30;

  public function __construct()
  {
    $curl = new Curl('https://www.googleapis.com');
    $curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
    $this->yts = $curl;
    $this->setKey($this->apikey);
  }

  public static function getInstance()
  {
    if (null === self::$_instance) {
      self::$_instance = new self();
    }

    return self::$_instance;
  }

  public function api_search($str = null)
  {
    if (empty($this->query['q'])) {
      if (empty($str)) {
        throw new \Exception('Keyword is empty', 1);
      } else {
        $this->setKey($str);
      }
    }

    $cfg_detail = Helper::_file_(__DIR__ . '/json/' . md5(serialize($this->query)) . '.json');
    $date_expired = true;
    if (file_exists($cfg_detail)) {
      $timest = strtotime('+' . $this->getExpires() . ' days', filectime($cfg_detail));
      $this->response_data['expired_timestamp'] = $timest;
      $days_ago = date('Y-m-d', $timest);
      $this->response_data['expired_formatted'] = $days_ago;
      $date_expired = $days_ago < date('Y-m-d');
      $this->response_data['expired'] = $date_expired;
    }
    if (empty(Helper::fget($cfg_detail))) {
      $date_expired = true;
    }
    if ($date_expired) {
      $this->yts->get('/youtube/v3/search', $this->query);
      if (!$this->yts->error) {
        $this->response_data['error'] = false;
        $this->response_data = array_merge($this->response_data, (array) $this->yts->response);
      }
      Helper::fwrite($cfg_detail, json_encode((object) $this->response_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    } else {
      $result = Helper::fget($cfg_detail);
      $this->response_data = json_decode($result);
    }

    return (object) $this->response_data;
  }

  public function setExpires($n)
  {
    if (!is_numeric($n) || $n < 1) {
      throw new Exception('Format expires must be numeric, and minimum days is 1', 1);
    }
    $this->expyt = $n;

    return $this;
  }

  public function allow_cors()
  {
    if (!headers_sent()) {
      header('Access-Control-Allow-Origin: *');
    }

    return $this;
  }

  public function getExpires()
  {
    return $this->expyt;
  }

  public function setMax($n)
  {
    $this->query['maxResult'] = $n;

    return $this;
  }

  /**
   * Set API KEY.
   *
   * @param string $str
   *
   * @return YTSearch
   */
  public function setKey($str)
  {
    if (!is_string($str)) {
      throw new Exception('API KEY must be type string instead of ' . gettype($str), 1);
    }
    $this->query['key'] = $str;

    return $this;
  }

  /**
   * Set API KEY
   * {@inheritdoc} setKey
   *
   * @param string $str
   *
   * @return YTSearch
   */
  public static function key($str)
  {
    self::setKey($str);

    return self::getInstance();
  }

  public function setKeyword($str)
  {
    $this->query['q'] = $str;

    return $this;
  }

  public function header_json()
  {
    if (!headers_sent()) {
      header('Content-Type: application/json');
    }

    return $this;
  }
}
