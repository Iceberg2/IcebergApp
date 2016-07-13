<?php

namespace IcebergApp\Core;

use Dotenv\Dotenv;

class Environment extends Dotenv
{

  public function get($name, $default = null)
  {
    $value = $this->loader->getEnvironmentVariable($name);
    return $value === null ? $default : $value;
  }

}
