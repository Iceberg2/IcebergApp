<?php

namespace IcebergApp\Core;

use IcebergApp\Support\Collection;

class Settings extends Collection
{

  private $service_key;

  public function setServiceKey($service_key)
  {

    $this->service_key = $service_key;
  }

  public function getServiceKey()
  {
    return $this->service_key;
  }

  public function getService($key, $default = null)
  {
    return $this->get($this->makeServiceFieldKey($key), $default);
  }

  public function makeServiceFieldKey($key)
  {
    if (is_array($key))
    {
      $key = implode('.', $key);
    }
    if (is_string($key) && !empty($key))
    {
      return $this->service_key . '.' . $key;
    }
    return $this->service_key;
  }

}
