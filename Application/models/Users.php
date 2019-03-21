<?php

namespace Application\models;


use PDO;
use Application\database\Query;
class Users 
{

  public static function find ()
  {
    return Query::run('SELECT * FROM f_find_users()')->fetchAll(PDO::FETCH_ASSOC);
  }

  public function save (array $data = array())
  {
    return Query::run('SELECT f_save_users(?, ?, ?, ?)', $data)->fetch(PDO::FETCH_ASSOC)['f_save_users'];
  }

  public function delete ($id)
  {
    return Query::run('SELECT f_delete_users(?)', array($id))
                                  ->fetch(PDO::FETCH_ASSOC)['f_delete_users'];
  }

  public function findById ($id) 
  {
    return Query::run('SELECT * FROM f_findById_users(?)', array($id))->fetch(PDO::FETCH_ASSOC);
  }
}