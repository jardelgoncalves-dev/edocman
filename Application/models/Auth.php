<?php

namespace Application\models;

use PDO;
use Application\database\Query;
class Auth
{
  public function findUserByEmailAndPassword(array $credential = array())
  {
      return Query::run('SELECT f_user_auth(?, ?)', $credential)
                                ->fetch(PDO::FETCH_ASSOC)['f_user_auth'];
  }
}