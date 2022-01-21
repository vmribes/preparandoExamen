<?php
namespace App;


use Exception;
use InvalidArgumentException;
class Registry
{
    public const LOGGER = 'LOGGER';
    public const PDO = 'PDO';
    public const ROUTER = 'router';

    private static array $services = [];

    private static array $allowedKeys = [
        self::LOGGER,
        self::PDO,
        self::ROUTER
    ];

    public static function set(string $key, $value)
    {
        if (!in_array($key, self::$allowedKeys)) {
            throw new Exception('Invalid key given');
        }

        self::$services[$key] = $value;
    }

    public static function get(string $key)
    {
        if (!in_array($key, self::$allowedKeys) || !isset(self::$services[$key])) {
            throw new InvalidArgumentException('Invalid key given');
        }
        return self::$services[$key];
    }
}