<?php

namespace IcebergApp\Service;

use IcebergApp\Contracts\Service\Manager as ManagerServiceContract;

abstract class AbstractManager implements ManagerServiceContract
{

  protected $service;

  public final function __construct()
  {
    //
  }

  public function getService()
  {
    return $this->service;
  }

  public function setService($service)
  {
    $this->service = $service;
  }

}
