<?php
namespace Application\core\interfaces;
interface ResponseInterface
{
    public function addHeader(string $name, string $value);
    public function setContentType(string $content_type);
    public function statusCode(int $code);
    public function body(array $data = array());
    public function sendJSON();
    public static function redirect(string $url);
}