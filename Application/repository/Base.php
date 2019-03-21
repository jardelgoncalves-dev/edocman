<?php

namespace Application\repository;

use Application\helpers\Validator;
class Base
{

  protected $validator = null;
  protected $response = array();
  protected $model = null;
  
  
  public function __construct(Validator $validator)
  {
    $this->validator = $validator;
  }

  private function attrValueDefault($variable, $default) {
    return $variable === null ? $default : $variable;
  }
  
  public function call_method($instance, string $method, $param = null,
                                int $code = 200, $message_error = 'Not found!')
  {
    $code = $this->attrValueDefault($code, 200);
    $message_error = $this->attrValueDefault($message_error, 'Not found');
    $param = $this->attrValueDefault($param, null);

    if (!$this->validator->getErrors()) {
        if (method_exists($instance, $method)) {
            $result = call_user_func([$instance, $method], $param);
            if (!$result) {
                $code = 404;
                $this->response = array('error' => $message_error);
            } else {
              $this->response = array('data' => is_bool($result) ? 'success' : $result);
            }
        } else {
            $code = 500;
            $this->response = array('error' =>'There was an internal error. Contact the administrator!');
        }

    } else {
      $code = 400;
      $this->response = array('error' =>$this->validator->getErrors());
    }

    return array(
      'code' => $code,
      'response' => $this->response
    );
  }
}