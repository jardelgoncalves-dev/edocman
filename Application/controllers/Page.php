<?php


namespace Application\controllers;

use Application\core\Controller;
use Application\core\security\CSRF;
use Application\core\http\Response;
use Application\core\session\Session;
use Application\helpers\Validator;
use Application\repository\Base;
class Page extends Controller
{
  protected $response = null;
  protected $session = null;
  public function __construct()
  {
    $this->session = new Session();
    $this->response = new Response();
  }

  public function index()
  {
    $this->session->destroy();
    return $this->render('login/index');
  }

  public function login()
  {
    if (CSRF::is_valid()){
      $validator = new Validator(array(
        'email.required'    => @$_POST['email'],
        'email.email'       => @$_POST['email'],
        'password.required' => @$_POST['password']
      ));
      $RepositoryBase = new Base($validator);

      $data = $RepositoryBase->call_method($this->model('Auth'), 'findUserByEmailAndPassword', array(
        @$_POST['email'], @md5($_POST['password'])), 200, 'Invalid email or password!');
      
      $this->response->statusCode($data['code'])->setContentType('application/json');

      if ($data['code'] === 200) {
        $user = $RepositoryBase->call_method($this->model('Users'), 'findByEmail', 
                                            $_POST['email'], 200, array());
        
        if (count($user['response']['data']) > 0) {
          $this->session->set('user', $user['response']['data']);
        } else {
          $this->session->destroy();
        }

        $this->response->body(array('auth' => '/home'))->sendJSON();
      } else {
        $this->response->body($data['response'])->sendJSON();
      }

    } else {
      Response::redirect('/error/notAllow');
    }
  }

  public function logout()
  {
    
    $this->session->destroy();
    Response::redirect('/');
  }

}