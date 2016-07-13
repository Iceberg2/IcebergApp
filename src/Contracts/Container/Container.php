<?php

namespace IcebergApp\Contracts\Container;

interface Container
{

  public function get($id);

  public function has($id);
}
