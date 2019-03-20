<?php

namespace Application\repository;

use Application\helpers\Validator;
class Base
{

  protected $validator = null;
  protected $response = array();
  
  
  public function __construct(Validator $validator)
  {
    $this->validator = $validator;
  }

  private function attrValueDefault($variable, $default) {
    return $variable === null ? $default : $variable;
  }

  private function model($model)
  {
    if (class_exists('Application\\models\\' . $model)) {
      $model = 'Application\\models\\' . $model;
      return new $model();
    } else {
      throw new Exception("Class not found!", 6000);
    }
  }

  public function call_method(string $model, string $method, array $params = array(),
                                int $code = 200, $message_error = 'Not found!')
  {
    $code = $this->attrValueDefault($code, 200);
    $message_error = $this->attrValueDefault($message_error, 'Not found');
    $params = $this->attrValueDefault($params, array());

    $message_error = $message_error === null ?  200 : $code;

    if (!$this->validator->getErrors()) {
        $intance = $this->model($model);
    
        if (method_exists($intance, $method)) {
            $result = call_user_func([$intance, $method], $params);
            if (!$result) {
                $code = 404;
                $this->response = $message_error;
            } else {
              $this->response = $result;
            }
        } else {
            $code = 500;
            $this->response = 'There was an internal error. Contact the administrator!';
        }

    } else {
      $code = 400;
      $this->response = $this->validator->getErrors();
    }

    return array(
      'code' => $code,
      'response' => $this->response
    );
  }
}