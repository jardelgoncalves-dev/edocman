<?php

namespace Application\controllers;


use Application\core\Controller;
use Application\core\http\Response;
use Application\repository\Base;
use Application\helpers\Validator;
class Tag extends Controller
{
  protected $response = null;
  public function __construct()
  {
    $this->response = new Response();
  }

  public function all()
  {
    $RepositoryBase = new Base(new Validator());
    $data = $RepositoryBase->call_method($this->model('Tags'), 'find', null, 200);
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
    $data = $RepositoryBase->call_method($this->model('Tags'), 'findById', $id, 200, 'Tag not found!');
    $this->response->statusCode($data['code'])->setContentType('application/json')
                   ->body($data['response'])->sendJSON();
  }

  public function store()
  {
    $validator = new Validator(array(
      'name.required' => $_POST['name']
    ));

    $RepositoryBase = new Base($validator);
    $data = $RepositoryBase->call_method($this->model('Tags'), 'save', $_POST, 201);
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
    $data = $RepositoryBase->call_method($this->model('Tags'), 'delete', $id, 200, 'User not found!');
    $this->response->statusCode($data['code'])->setContentType('application/json')
                   ->body($data['response'])->sendJSON();
  }
}