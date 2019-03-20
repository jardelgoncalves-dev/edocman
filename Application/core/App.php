<?php

namespace Application\core;

class App
{
  protected $controller = 'Page';
  protected $method = 'index';
  protected $params = array();
  protected $enabled_error = true;

  public function __construct()
  {
    $URI = $this->parseUrl();
    $this->getControllerFromUrl($URI);
    $this->getMethodFromUrl($URI);
    $this->getParamsFromUrl($URI);

    call_user_func_array([$this->controller, $this->method], $this->params);
  }

  protected function parseUrl()
  {
    return explode('/', substr(filter_var(filter_input(INPUT_SERVER, 'REQUEST_URI'), FILTER_SANITIZE_URL), 1));
  }

  protected function getControllerFromUrl($uri)
  {
    if (isset($uri[0]) && !empty($uri[0])) {
      if (class_exists('Application\\controllers\\' . ucfirst($uri[0]))) {
        $this->controller = ucfirst($uri[0]);
        $this->enabled_error = false;
      }
    }
    
    $this->controller = $this->instanceController($this->controller);
  }

  protected function getMethodFromUrl($uri)
  {
    if (isset($uri[1]) && !empty($uri[1])) {
      if (method_exists($this->controller, $uri[1]) && !$this->enabled_error) {
        $this->method = $uri[1];
      } else {
        $this->callControllerError();
      }
    }
  }

  protected function getParamsFromUrl($uri)
  {
    $this->params = count($uri) > 2 ? array_slice($uri, 2) : array();
  }

  protected function instanceController($name)
  {
    $instance = 'Application\\controllers\\' . $name;
    return  new $instance();
  }

  private function callControllerError($controller = 'Error', $method = 'notFound')
  {
    $this->controller = $this->instanceController($controller);
    $this->method = $method;
  }

}