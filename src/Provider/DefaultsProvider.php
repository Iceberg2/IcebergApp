<?php

namespace Articstudio\IcebergApp\Provider;

use Articstudio\IcebergApp\Contract\Provider as ProviderContract;
use Articstudio\IcebergApp\Contract\Container as ContainerContract;
use Articstudio\IcebergApp\Environment;
use Illuminate\Database\Capsule\Manager AS DBManager;
use Articstudio\IcebergApp\Security\Token;
use Articstudio\IcebergApp\Security\Hash;

class DefaultsProvider implements ProviderContract {

    public function register(ContainerContract $container) {
        $this->registerEnvironment($container);
        $this->registerDatabase($container);
        $this->registerHash($container);
        $this->registerToken($container);
    }

    public function registerEnvironment(ContainerContract $container) {
        if (!isset($container['env'])) {
            $container['env'] = function ($container) {
                $env = new Environment($container->settings->get('env_path'), $container->settings->get('env_file'));
                $env->load();
                return $env;
            };
        }
    }

    public function registerDatabase(ContainerContract $container) {
        if (!isset($container['db'])) {
            $container['db'] = function ($container) {
                $db = new DBManager();
                $db->addConnection([
                    'driver' => 'mysql',
                    'host' => $container->env->get('DB_HOST', 'localhost'),
                    'database' => $container->env->get('DB_NAME', 'iceberg'),
                    'username' => $container->env->get('DB_USER', 'root'),
                    'password' => $container->env->get('DB_PASSWORD', ''),
                    'charset' => 'utf8',
                    'collation' => 'utf8_unicode_ci',
                    'prefix' => '',
                ]);
                $db->setAsGlobal();
                $db->bootEloquent();
                return $db;
            };
        }
    }

    public function registerHash(ContainerContract $container) {
        $container['hash'] = function ($container) {
            return new Hash([
                Hash::KEY_DEFAULT => $container->env->get('SALT_DEFAULT', ''),
                Hash::KEY_NONCE => $container->env->get('SALT_NONCE', ''),
                Hash::KEY_AUTH => $container->env->get('SALT_AUTH', ''),
                Hash::KEY_SESSION => $container->env->get('SALT_SESSION', '')
            ]);
        };
    }

    public function registerToken(ContainerContract $container) {
        if (!isset($container['token'])) {
            $container['token'] = function ($container) {
                return new Token($container->hash->get(Hash::KEY_AUTH));
            };
        }
    }

}
