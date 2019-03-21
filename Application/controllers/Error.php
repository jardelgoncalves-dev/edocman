<?php


namespace Application\controllers;

use Application\core\Controller;
use Application\core\security\CSRF;
use Application\core\http\Response;
class Error extends Controller
{
  protected $response = null;
  public function __construct()
  {
    $this->response = new Response();
  }

  // public function index()
  // {
  //   return $this->internal();
  // }

  public function notFound()
  {
    return $this->render('error/page404');
  }

  public function notAllow()
  {
    return $this->render('error/page403');
  }

  public function internal()
  {
    return $this->render('error/page500');

  }

}