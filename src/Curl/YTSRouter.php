<?php

namespace Curl;

class YTSRouter
{
  public static $request;
  public static $form;
  public static $dir;

  public function __construct()
  {
  }

  public function get($path)
  {
  }

  public static function setForm($folder)
  {
    self::$form = $folder;
  }

  public static function setDir($dir)
  {
    self::$dir = $dir;
  }

  public static function start()
  {
    if (!self::$dir) {
      throw new \Exception('Error Processing Request', 1);
    }
    if (!is_dir(self::$dir . '/views')) {
      mkdir(self::$dir . '/views');
    }
    self::$request = $_SERVER['REQUEST_URI'];
    switch (self::$request) {
      case '/':
        require self::$dir . '/views/index.php';
        break;
      case '':
        require self::$dir . '/views/index.php';
        break;
      case preg_match('/^\/public\/search/s', self::$request) === 1:
        require self::$dir . '/views/search.php';
        break;
      default:
        http_response_code(404);
        echo "404 not found";
        require self::$dir . '/views/404.php';
        break;
    }
  }
}
