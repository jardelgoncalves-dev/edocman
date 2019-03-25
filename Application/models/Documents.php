<?php

namespace Application\models;

use PDO;
use Application\database\Query;
use thiagoalessio\TesseractOCR\TesseractOCR;
use Application\helpers\Miscellaneous;
use thiagoalessio\TesseractOCR\UnsuccessfulCommandException;
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

  public function searchDocument (array $data = array())
  {
    $favorable_documents = array();
    if ($data[1] === 'simple') {
      return Query::run('SELECT * FROM find_documents_simple(?)', array($data[0]))->fetchAll(PDO::FETCH_ASSOC);
    } else if ($data[1] === 'advanced') {
      $documents = Query::run('SELECT * FROM f_find_documents()')->fetchAll(PDO::FETCH_ASSOC);
      
      foreach($documents as $document){
        try {
          $tesseract = new TesseractOCR(@Miscellaneous::base64_to_jpeg($document['image'], 'file.png'));
          $text = $tesseract->run();
          if (Miscellaneous::word_in_string($text, $data[0])) {
            array_push($favorable_documents, $document);
          }
        } catch(UnsuccessfulCommandException $e) {
          // LOGGER
        }

      }

      return $favorable_documents;
    }
    return $favorable_documents;
  }

  public function findByTagId ($id) 
  {
    return Query::run('SELECT * FROM find_documentsByTagId(?)', array($id))->fetchAll(PDO::FETCH_ASSOC);
  }
}