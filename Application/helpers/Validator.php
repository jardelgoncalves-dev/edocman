<?php
namespace Application\helpers;

use finfo;
final class Validator
{
    private $errors = [];
    
    public function __construct(array $datas = array(), array $messages = array())
    {
        $this->run_validators($datas, $messages);
    }

    public function required($variable, string $name, string $message)
    {
        if (!isset($variable) || empty($variable)) {
            $this->errors[$name][] = empty($message) ? $name . ' is required!' : $message;
        }
    }

    public function integer($variable, string $name, string $message)
    {
        if (filter_var($variable, FILTER_VALIDATE_INT) === false) {
            $this->errors[$name][] = empty($message) ? $name . ' is not valid!' : $message;
        }
    }

    public function float($variable, string $name, string $message)
    {
        if (filter_var($variable, FILTER_VALIDATE_FLOAT) === false) {
            $this->errors[$name][] = empty($message) ? $name . ' is not valid!' : $message;
        }
    }

    public function email($variable, string $name, string $message)
    {
        if (filter_var($variable, FILTER_VALIDATE_EMAIL) === false) {
            $this->errors[$name][] = empty($message) ? 'Invalid email!' : $message;
        }
    }

    public function file($variable, string $name, string $message)
    {
        $fileinfo = new finfo(FILEINFO_MIME_TYPE);
        $mimetype = @$fileinfo->file($variable);
        $valid_extensions = array("image/jpg","image/jpeg","image/png");

        if (!in_array(strtolower($mimetype), $valid_extensions)) {
            $this->errors[$name][] = empty($message) ? 'Invalid file type!' : $message;
        }
    }

    

    public function has($key)
    {
        return isset($this->errors[$key]);
    }

    public function first($key)
    {
        return isset($this->errors[$key]) ? $this->errors[$key][0] : false;
    }

    public function last($key)
    {
        return isset($this->errors[$key]) ? $this->errors[$key][count($this->errors[$key])  -1] : false;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    private function get_message_array(array $messages = array(), $item)
    {
        $msg = '';
        foreach ($messages as $key => $message):
            if ($key === $item):
                $msg = $message;
        endif;
        endforeach;
        return $msg;
    }

    private function run_validators(array $datas = array(), array $messages = array())
    {
        foreach ($datas as $item => $value):
            $name_method = explode('.', $item);
        $message = $this->get_message_array($messages, $item);
        call_user_func_array([$this,$name_method[1]], [$value, $name_method[0], $message]);
        endforeach;
    }
}
