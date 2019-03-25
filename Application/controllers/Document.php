<?php

namespace Application\controllers;


use Application\core\Controller;
use Application\core\http\Response;
use Application\repository\Base;
use Application\helpers\Validator;
use Application\helpers\Miscellaneous;
class Document extends Controller
{
  protected $response = null;
  public function __construct()
  {
    $this->response = new Response();
  }

  public function all()
  {
    $RepositoryBase = new Base(new Validator());
    $data = $RepositoryBase->call_method($this->model('Documents'), 'find', null, 200);
    $this->response->statusCode($data['code'])->setContentType('application/json')
                   ->body($data['response'])->sendJSON();
  }

  public function find($id = null)
  {
    $validator = new Validator(array(
      'id.required' => $id,
      'id.integer'  => $id
    ));

    $RepositoryBase = new Base($validator);
    $data = $RepositoryBase->call_method($this->model('Documents'), 'findById', $id, 200, 'Document not found!');
    $this->response->statusCode($data['code'])->setContentType('application/json')
                   ->body($data['response'])->sendJSON();
  }

  public function store()
  {
    $validator = new Validator(array(
      'filename.required' => @$_POST['filename'],
      'size.required'     => @$_FILES['image']['size'],
      'size.float'        => @$_FILES['image']['size'],
      'author.required'   => @$_POST['author'],
      'tag_id.required'   => @$_POST['tag_id'],
      'tag_id.integer'    => @$_POST['tag_id'],
      'image.required'    => @$_FILES['image']['tmp_name'],
      'image.file'        => @$_FILES['image']['tmp_name']
    ));

    $document = array(
      'filename' => @$_POST['filename'],
      'size'     => @$_FILES['image']['size'],
      'author'   => @$_POST['author'],
      'tag_id'   => @$_POST['tag_id'],
      'image'    => Miscellaneous::getBase64FromFile(@$_FILES['image']['tmp_name']),
    );
    

    $RepositoryBase = new Base($validator);
    $data = $RepositoryBase->call_method($this->model('Documents'), 'save', $document, 201);
    $this->response->statusCode($data['code'])->setContentType('application/json')
                   ->body($data['response'])->sendJSON();
  }

  public function destroy($id = null)
  {
    $validator = new Validator(array(
      'id.required' => $id,
      'id.integer'  => $id
    ));

    $RepositoryBase = new Base($validator);
    $data = $RepositoryBase->call_method($this->model('Documents'), 'delete', $id, 200, 'Document not found!');
    $this->response->statusCode($data['code'])->setContentType('application/json')
                   ->body($data['response'])->sendJSON();
  }

  public function search($keyword = null, $type = 'simple')
  {
    $validator = new Validator(array(
      'keyword.required' => $keyword,
      'type.required'    => $type
    ));
    $RepositoryBase = new Base($validator);
    $data = $RepositoryBase->call_method($this->model('Documents'), 'searchDocument',
                                  array($keyword, $type), 200, 'Document not found!');
    $this->response->statusCode($data['code'])->setContentType('application/json')
                   ->body($data['response'])->sendJSON();
  }

  public function tag($id = null)
  {
    $validator = new Validator(array(
      'id.required' => $id,
      'id.integer'  => $id
    ));

    $RepositoryBase = new Base($validator);
    $data = $RepositoryBase->call_method($this->model('Documents'), 'findByTagId', $id, 200, 'Document not found!');
    $this->response->statusCode($data['code'])->setContentType('application/json')
                   ->body($data['response'])->sendJSON();
  }
}