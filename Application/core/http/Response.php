<?php

namespace Application\core\http;

use Application\core\interfaces\ResponseInterface;

class Response implements ResponseInterface
{
  private $status_code = 'HTTP/1.1 200';
  private $headers = [];
  private $data;
  private function createHeaderResponse()
  {
      header($this->status_code);
      foreach ($this->headers as $name => $value) {
          header($name . ': ' . $value);
      }
  }

  public function addHeader(string $name, string $value)
  {
      $this->headers[$name][] = $value;
      return $this;
  }

  public function setContentType(string $content_type)
  {
      $this->headers['Content-Type'] = $content_type;
      return $this;
  }

  public function statusCode(int $code)
  {
      $this->status_code = 'HTTP/1.1 ' . $code;
      return $this;
  }

  public function body(array $data = array())
  {
      $this->data = $data;
      return $this;
  }

  public function sendJSON()
  {
      $this->createHeaderResponse();
      echo json_encode($this->data);
  }

  public static function redirect(string $url)
  {
      header('location: ' . $url);
  }
}
