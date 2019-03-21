<?php

namespace Application\models;

use PDO;
use Application\database\Query;
class Documents
{
  public static function find ()
  {
    return Query::run('SELECT * FROM f_find_documents()')->fetchAll(PDO::FETCH_ASSOC);
  }

  public function save (array $data = array())
  {
    return Query::run('SELECT f_save_documents(?, ?, ?, ?, ?)', $data)->fetch(PDO::FETCH_ASSOC)['f_save_documents'];
  }

  public function delete ($id)
  {
    return Query::run('SELECT f_delete_documents(?)', array($id))
                                  ->fetch(PDO::FETCH_ASSOC)['f_delete_documents'];
  }

  public function findById ($id) 
  {
    return Query::run('SELECT * FROM f_findById_documents(?)', array($id))->fetch(PDO::FETCH_ASSOC);
  }
}