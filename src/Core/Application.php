<?php

namespace IcebergApp\Core;

use IcebergApp\Container\Container;
use IcebergApp\Contracts\Container\Container as ContainerContract;
use IcebergApp\Container\Provider as ContainerProvider;
use IcebergApp\Contracts\Service\Manager AS ManagerServiceContract;
use IcebergApp\Exception\Service\ManagerNotFoundException;
use IcebergApp\Exception\Service\InvalidManagerException;
use IcebergApp\Exception\Service\InvalidMiddlewareException;

class Application
{

  use \IcebergApp\Contracts\Exception\ThrowBy;

  const VERSION = '2.0.0';

  private $container;

  public function __construct($container = [])
  {
    if (is_array($container))
    {
      $container = new Container($container);
    }
    if (!$container instanceof ContainerContract)
    {
      throw new InvalidArgumentException('Expected a ContainerInterface');
    }
    $this->container = $container;
    Container::setInstance($this->container);
    /* $this->container['app'] = function() use ($this) {
      return $this;
      }; */
  }

  public function init()
  {
    $provider = new ContainerProvider();
    $provider->register($this->container);
    return $this;
  }

  public function load($service_key)
  {
    $this->container->settings->setServiceKey($service_key);
    $manager = $this->container->settings->getService('manager');
    if ($manager)
    {
      $manager = new $manager();
      if ($manager instanceof ManagerServiceContract)
      {
        $manager->load($this->container->settings->getService('load', []));
        $this->container['manager'] = function($container) use ($manager)
        {
          return $manager;
        };
      }
      else
      {
        throw new InvalidManagerException('Expected a \IcebergApp\Contracts\Service\Manager');
      }
    }
    else
    {
      throw new ManagerNotFoundException('Manager class not found');
    }
    return $this;
  }

  public function config()
  {
    try
    {
      $this->container->manager->middleware($this->container->settings->getService('middleware', []));
    }
    catch (\InvalidArgumentException $exception)
    {
      if ($this->exceptionThrownBy($exception, $this->container->settings->getService('manager'), \InvalidArgumentException::class, 'middleware'))
      {
        throw new InvalidMiddlewareException('Invalid Middleware', null, $exception);
      }
      else
      {
        throw $exception;
      }
    }
    $this->container->manager->config($this->container->settings->getService('config', []));
    return $this;
  }

  public function run()
  {
    $this->container->manager->run($this->container->settings->getService('run', []));
    return $this;
  }

  public function version()
  {
    return static::VERSION;
  }

}
