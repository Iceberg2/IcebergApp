<?php

namespace IcebergApp\Container;

use IcebergApp\Contracts\Provider\Container as ContainerProviderContract;
use IcebergApp\Contracts\Container\Container as ContainerContract;
use Pimple\Container AS PimpleContainer;
use IcebergApp\Core\Environment;
use Illuminate\Database\Capsule\Manager AS DBManager;
use IcebergApp\Security\Token;
use IcebergApp\Security\Hash;

class Provider implements ContainerProviderContract
{

  private $db;

  public function register(PimpleContainer $container)
  {
    $this->registerEnvironment($container);
    $this->registerDatabase($container);
    $this->registerToken($container);
    $this->registerHash($container);
  }

  protected function registerEnvironment(ContainerContract $container)
  {
    if (!isset($container['env']))
    {
      $container['env'] = function ($container)
      {
        $env = new Environment($container->settings->get('env.path', __DIR__), $container->settings->get('env.file', '.env'));
        $env->load();
        return $env;
      };
    }
  }

  protected function registerDatabase(ContainerContract $container)
  {
    if (!isset($container['db']))
    {
      $this->db = new DBManager();
      $this->db->addConnection([
        'driver' => 'mysql',
        'host' => $container->env->get('DB_HOST', 'localhost'),
        'database' => $container->env->get('DB_NAME', 'iceberg'),
        'username' => $container->env->get('DB_USER', 'root'),
        'password' => $container->env->get('DB_PASSWORD', ''),
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix' => '',
      ]);
      $this->db->setAsGlobal();
      $this->db->bootEloquent();
      $p = $this;
      $container['db'] = function ($container) use ($p)
      {
        return $p->db;
      };
    }
  }

  protected function registerToken(ContainerContract $container)
  {
    if (!isset($container['token']))
    {
      $container['token'] = function ($container)
      {
        return new Token($container->env->get('SALT_AUTH', ''));
      };
    }
  }

  protected function registerHash(ContainerContract $container)
  {
    if (!isset($container['hash']))
    {
      $container['hash'] = function ($container)
      {
        return new Hash([
          'default' => $container->env->get('SALT_DEFAULT', ''),
          'nonce' => $container->env->get('SALT_NONCE', ''),
          'auth' => $container->env->get('SALT_AUTH', ''),
          'session' => $container->env->get('SALT_SESSION', '')
        ]);
      };
    }
  }

}
