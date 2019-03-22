<?php

namespace Application\core\security;

use Application\core\session\Session;
use Application\core\http\Response;
class Auth extends Session
{

  private static $to_login_page = '/';

  public static function get_user()
  {
    self::init();
    return self::has('user') ? self::get('user') : false;
  }

  public static function onlyAuthenticated()
  {
    if (!self::has('user')) {
      Response::redirect(self::$to_login_page);
    }
  }
}