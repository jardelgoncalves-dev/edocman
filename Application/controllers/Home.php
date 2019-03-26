<?php

namespace Application\controllers;

use Application\core\Controller;
use Application\core\security\Auth;
use Application\core\session\Session;
use Application\helpers\Validator;
class Home extends Controller
{
  protected $session = null;
  public function __construct()
  {
    $this->session = new Session();
    Auth::onlyAuthenticated();
  }

  public function index()
  {

    $this->render('dashboard/index');
  }

  public function document ()
  {
    $tags = $this->model('Tags')->find();
    $this->render('dashboard/document', compact('tags'));
  }

  public function user ()
  {
    $access_level = $this->model('AccessLevel')->find();
    $this->render('dashboard/user', compact('access_level'));
  }

  public function tag ()
  {
    $this->render('dashboard/tag');
  }

  public function view($id)
  {
    $validator = new Validator(array(
      'id.required' => $id,
      'id.integer' => $id
    ));

    $document = array();

    if (!$validator->getErrors()) {
      $document = $this->model('Documents')->findById($id);
    }

    return $this->render('dashboard/view', $document ? $document : array());
  }
}