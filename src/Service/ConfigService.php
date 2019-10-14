<?php

namespace App\Service;

use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

/**
 * Class ConfigService
 * @package Service
 */
class ConfigService
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * ConfigService constructor.
     */
    public function __construct()
    {
        $path = dirname(__FILE__) . '/../../app/config.yml';
        if (file_exists($path)) {
            $this->data = Yaml::parseFile($path);
        } else {
            throw new ParseException('Не найден файл конфига');
        }
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key)
    {
        return $this->data[$key] ?? null;
    }

    /**
     * @return array
     */
    public function getAll()
    {
        return $this->data;
    }
}