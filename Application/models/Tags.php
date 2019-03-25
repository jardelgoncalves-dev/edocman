<?php

namespace Application\models;

use PDO;
use Application\database\Query;
class Tags
{
  public static function find ()
  {
    return Query::run('SELECT * FROM f_find_tags()')->fetchAll(PDO::FETCH_ASSOC);
  }

  public function save (array $data = array())
  {
    return Query::run('SELECT f_save_tags(?)', $data)->fetch(PDO::FETCH_ASSOC)['f_save_tags'];
  }

  public function delete ($id)
  {
    return Query::run('SELECT f_delete_tags(?)', array($id))
                                  ->fetch(PDO::FETCH_ASSOC)['f_delete_tags'];
  }

  public function findById ($id) 
  {
    return Query::run('SELECT * FROM f_findById_tags(?)', array($id))->fetch(PDO::FETCH_ASSOC);
  }

  public function getInfoTags()
  {
    return Query::run('SELECT * FROM f_info_tags()')->fetchAll(PDO::FETCH_ASSOC);
  }
}