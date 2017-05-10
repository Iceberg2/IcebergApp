<?php

namespace Articstudio\IcebergApp\Security;

use Articstudio\IcebergApp\Support\Collection;

class Hash {

    const KEY_DEFAULT = 'default';
    const KEY_NONCE = 'nonce';
    const KEY_AUTH = 'auth';
    const KEY_SESSION = 'session';

    protected $salt;

    public function __construct(array $salt = []) {
        $this->salt = new Collection($salt);
    }

    public function get($key = 'default') {
        return $this->salt->get($key);
    }

    public function set($key, $value = null) {
        return $this->salt->put($key, $value);
    }

    public function make($string) {
        return static::Crypt($string, $this->get(self::KEY_DEFAULT));
    }

    public function makeNonce($string) {
        return static::Crypt($string, $this->get(self::KEY_NONCE));
    }

    public function makeAuth($string) {
        return static::Crypt($string, $this->get(self::KEY_AUTH));
    }

    public function makeSession($string) {
        return static::Crypt($string, $this->get(self::KEY_SESSION));
    }

    public static function Crypt($string, $salt = null) {
        return crypt($string, $salt);
    }

    public static function Verify($string, $hash) {
        return hash_equals($hash, crypt($string, $hash));
    }

}
