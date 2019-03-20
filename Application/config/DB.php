<?php

namespace Application\config;

use Dotenv\Dotenv;


class DB
{
  private function getValue($key, $default)
  {
    $env = Dotenv::create(__DIR__ . '/../../');
    $env->load();

    return getenv($key) !== false ? getenv($key) : $default;
  }

  private function getInfo()
  {
    return array(
      'DBNAME' => self::getValue('DBNAME', 'homested'),

      'DBUSER' => self::getValue('DBUSER', 'postgres'),

      'DBPASSWORD' => self::getValue('DBPASSWORD', 'postgres'),

      'HOST' => self::getValue('HOST', 'localhost'),

      'PORT' => self::getValue('PORT', 5432),

      'DRIVER' => self::getValue('DRIVER', 'pgsql'),
    );
  }

  public static function getConfiguration():string
  {
    $config = self::getInfo();
    return $config['DRIVER'] . ':host=' . $config['HOST'] . ';port=' . 
           $config['PORT'] . ';dbname=' . $config['DBNAME'] . ';user=' . 
           $config['DBUSER'] . ';password=' . $config['DBPASSWORD'];
  }
}