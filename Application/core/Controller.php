<?php

namespace Application\core;

class Controller
{
  protected function render(string $view, array $data = array())
  {
    require '../Application/views/' . $view . '.php';
  }

  protected function model(string $model)
  {
    $model = 'Application\\models\\' . $model;
    return new $model();
  }
}