<?php
namespace Application\core\security;

use Application\core\session\Session;

class CSRF extends Session
{
  public static function token()
  {
    self::init();

    $token = sha1(uniqid(rand(), true));
    self::set('csrf_token', $token);
    
    return '<input id="csrf_token" type="hidden" name="csrf_token" value="' . $token . '" >';
  }
  
  public static function is_valid()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (self::get('csrf_token') === false || !isset($_POST['csrf_token'])) {
          return false;
      }
      if (self::get('csrf_token') !== $_POST['csrf_token']) {
          return false;
      }
      
      return true;
    }
    return true;
  }
}
