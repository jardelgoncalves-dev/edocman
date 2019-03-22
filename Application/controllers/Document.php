<?php

namespace Application\controllers;


use Application\core\Controller;
use Application\core\http\Response;
use Application\repository\Base;
use Application\helpers\Validator;
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
      'size.required'     => @$_POST['size'],
      'size.float'        => @$_POST['size'],
      'author.required'   => @$_POST['author'],
      'tag_id.required'   => @$_POST['tag_id'],
      'tag_id.integer'    => @$_POST['tag_id'],
      'image.required'    => @$_POST['image']
    ));

    $RepositoryBase = new Base($validator);
    $data = $RepositoryBase->call_method($this->model('Documents'), 'save', $_POST, 201);
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
}