<?php

namespace Application\core\interfaces;

interface SessionInterface
{
  public function setName($name);
  public function has($key);
  public function get($key);
  public function set($key, $value);
  public function destroy();
  
}