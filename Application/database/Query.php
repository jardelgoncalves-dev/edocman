<?php

namespace Application\database;

use PDO;
class Query extends PDO
{
    private function __construct(){}
    private function __clone(){}

    private function clean($array)
    {
        $array_aux = array();
        foreach ($array as $item):
            array_push($array_aux, filter_var($item, FILTER_SANITIZE_SPECIAL_CHARS));
        endforeach;
        return $array_aux;
    }
    
    public static function run(string $query, array $parameters = array())
    {
        $stmt = ConnectionSingleton::getInstance()->prepare($query);
        $stmt->execute(self::clean($parameters));
        return $stmt;
    }
}
