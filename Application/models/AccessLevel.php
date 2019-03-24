<?php

namespace Application\models;

use PDO;
use Application\database\Query;
class AccessLevel
{
  public static function find()
  {
      return Query::run('SELECT * FROM f_find_accesslevel()')->fetchAll(PDO::FETCH_ASSOC);
  }
}