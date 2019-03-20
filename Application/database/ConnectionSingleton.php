<?php

namespace Application\database;

use PDO;
use Application\config\DB;
class ConnectionSingleton 
{
  private static $conn = null;

  private function __construct(){}
  private function __clone(){}
  
  public static function getInstance()
  {
    if (self::$conn === null) {
      self::$conn = new PDO(DB::getConfiguration());
    }
     return self::$conn;
  }
}