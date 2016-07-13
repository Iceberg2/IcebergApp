<?php

namespace IcebergApp\Security;

use IcebergApp\Container\Container;

class Hash
{

  protected $salt = [];

  public function __construct(array $salt = [])
  {
    $this->salt = $salt;
  }

  public static function makeCustom($string, $salt = null)
  {
    if ($salt)
    {
      return crypt($string, $salt);
    }
    return crypt($string);
  }

  public static function verify($string, $hash)
  {
    return hash_equals($hash, crypt($string, $hash));
  }

  public static function make($string)
  {
    $hash = static::getInstance();
    return static::makeCustom($string, $hash->getSalt('default'));
  }

  public static function makeNonce($string)
  {
    $hash = static::getInstance();
    return static::makeCustom($string, $hash->getSalt('nonce'));
  }

  public static function makeAuth($string)
  {
    $hash = static::getInstance();
    return static::makeCustom($string, $hash->getSalt('auth'));
  }

  public static function makeSession($string)
  {
    $hash = static::getInstance();
    return static::makeCustom($string, $hash->getSalt('session'));
  }

  public function getSalt($key = 'default')
  {
    return isset($this->salt[$key]) ? $this->salt[$key] : null;
  }

  public function setSalt($key, $value = null)
  {
    return ($this->salt[$key] = $value);
  }

  public static function getInstance()
  {
    return Container::getInstance()->hash;
  }

}
