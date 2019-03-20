<?php

namespace Application\core\session;

use Application\core\interfaces\SessionInterface;
class Session implements SessionInterface
{

  public function __construct($name = 'mySessionName')
  {
    $this->setName($name);
    $this->init();
  }

  protected function init()
  {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }
  }
  public function setName($name = 'mySessionName')
  {
    session_name($name);
  }

  public function has($key)
  {
    return isset($_SESSION[$key]);
  }

  public function get($key)
  {
    return $this->has($key) ? $_SESSION[$key] : false;
  }

  public function set($key, $value)
  {
    $_SESSION[$key] = $value;
    return $this;
  }
  public function destroy()
  {
    $_SESSION = [];
    session_destroy();
    
    return $this;
  }
}