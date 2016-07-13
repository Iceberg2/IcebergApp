<?php

namespace IcebergApp\Container;

use Pimple\Container as PimpleContainer;
use IcebergApp\Contracts\Container\Container as ContainerContract;
use IcebergApp\Core\Settings;
use IcebergApp\Exception\Container\NotFoundException;
use IcebergApp\Exception\Container\InvalidArgumentException;

class Container extends PimpleContainer implements ContainerContract
{

  use \IcebergApp\Contracts\Exception\ThrowBy;

  protected static $instance;

  public function __construct(array $settings = [])
  {
    parent::__construct();
    $this['settings'] = function () use ($settings)
    {
      return new Settings($settings);
    };
  }

  public function get($id)
  {
    if (!$this->offsetExists($id))
    {
      throw new NotFoundException(sprintf('Identifier "%s" is not defined.', $id), null);
    }
    try
    {
      return $this->offsetGet($id);
    }
    catch (\InvalidArgumentException $exception)
    {
      if ($this->exceptionThrownByParent($exception, \InvalidArgumentException::class, 'offsetGet'))
      {
        throw new InvalidArgumentException(sprintf('Container error while retrieving "%s"', $id), null, $exception);
      }
      else
      {
        throw $exception;
      }
    }
  }

  public function has($id)
  {
    return $this->offsetExists($id);
  }

  public function __get($name)
  {
    return $this->get($name);
  }

  public function __isset($name)
  {
    return $this->has($name);
  }

  public static function getInstance()
  {
    return static::$instance;
  }

  public static function setInstance(ContainerContract $container)
  {
    static::$instance = $container;
  }

}
