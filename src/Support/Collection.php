<?php

namespace IcebergApp\Support;

class Collection
{

  protected $data = [];

  function __construct($arr = [])
  {
    if (is_array($arr))
    {
      $this->data = $arr;
    }
    else
    {
      throw new \InvalidArgumentException('Argument $arr must be an array');
    }
  }

  public function set($key, $value = null)
  {
    $this->data[$key] = $value;
    return $this;
  }

  public function import($arr)
  {
    $this->data = static::merge($this->data, $arr);
    return $this;
  }

  public function export()
  {
    return $this->data;
  }

  public function get($key, $default = null)
  {
    if (is_string($key) || is_array($key))
    {
      $key = is_array($key) ? implode('.', $key) : $key;
      return static::getValue($this->data, $key, $default);
    }
    throw new \InvalidArgumentException('ArrayHelper::get ERROR');
  }

  public static function merge($a, $b)
  {
    $args = func_get_args();
    $res = array_shift($args);
    while (!empty($args))
    {
      $next = array_shift($args);
      foreach ($next as $k => $v)
      {
        if (is_int($k))
        {
          if (isset($res[$k]))
          {
            $res[] = $v;
          }
          else
          {
            $res[$k] = $v;
          }
        }
        elseif (is_array($v) && isset($res[$k]) && is_array($res[$k]))
        {
          $res[$k] = self::merge($res[$k], $v);
        }
        else
        {
          $res[$k] = $v;
        }
      }
    }

    return $res;
  }

  public static function getValue($array, $key, $default = null)
  {
    if ($key instanceof \Closure)
    {
      return $key($array, $default);
    }

    if (is_array($key))
    {
      $lastKey = array_pop($key);
      foreach ($key as $keyPart)
      {
        $array = static::getValue($array, $keyPart);
      }
      $key = $lastKey;
    }

    if (is_array($array) && array_key_exists($key, $array))
    {
      return $array[$key];
    }

    if (($pos = strrpos($key, '.')) !== false)
    {
      $array = static::getValue($array, substr($key, 0, $pos), $default);
      $key = substr($key, $pos + 1);
    }

    if (is_object($array))
    {
      // this is expected to fail if the property does not exist, or __get() is not implemented
      // it is not reliably possible to check whether a property is accessable beforehand
      return $array->$key;
    }
    elseif (is_array($array))
    {
      return array_key_exists($key, $array) ? $array[$key] : $default;
    }
    else
    {
      return $default;
    }
  }

  public static function remove(&$array, $key, $default = null)
  {
    if (is_array($array) && (isset($array[$key]) || array_key_exists($key, $array)))
    {
      $value = $array[$key];
      unset($array[$key]);

      return $value;
    }

    return $default;
  }

  public static function index($array, $key)
  {
    $result = [];
    foreach ($array as $element)
    {
      $value = static::getValue($element, $key);
      $result[$value] = $element;
    }

    return $result;
  }

  public static function getColumn($array, $name, $keepKeys = true)
  {
    $result = [];
    if ($keepKeys)
    {
      foreach ($array as $k => $element)
      {
        $result[$k] = static::getValue($element, $name);
      }
    }
    else
    {
      foreach ($array as $element)
      {
        $result[] = static::getValue($element, $name);
      }
    }

    return $result;
  }

  public static function map($array, $from, $to, $group = null)
  {
    $result = [];
    foreach ($array as $element)
    {
      $key = static::getValue($element, $from);
      $value = static::getValue($element, $to);
      if ($group !== null)
      {
        $result[static::getValue($element, $group)][$key] = $value;
      }
      else
      {
        $result[$key] = $value;
      }
    }

    return $result;
  }

  public static function keyExists($key, $array, $caseSensitive = true)
  {
    if ($caseSensitive)
    {
      return array_key_exists($key, $array);
    }
    else
    {
      foreach (array_keys($array) as $k)
      {
        if (strcasecmp($key, $k) === 0)
        {
          return true;
        }
      }

      return false;
    }
  }

}
