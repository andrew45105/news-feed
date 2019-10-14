<?php

namespace App\Controller;

use App\Service\ConfigService;
use App\Service\DBService;
use App\Service\NewsService;
use App\Service\ResponseService;

/**
 * Class BaseController
 * @package Controller
 */
class BaseController
{
    /**
     * @var ConfigService
     */
    private $config;

    /**
     * @var DBService
     */
    private $db;

    /**
     * @var NewsService
     */
    private $news;

    /**
     * BaseController constructor.
     */
    public function __construct()
    {
        $this->config   = new ConfigService();
        $this->db       = new DBService($this->config);
        $this->news     = new NewsService($this->config, $this->db);
    }

    /**
     * Получение разметки страницы
     *
     * @param string $name
     * @param null $args
     *
     * @return string
     */
    public function getView(string $name, $args = null)
    {
        return (new ResponseService())->getView($name, $args);
    }

    /**
     * Получение json-строки
     *
     * @param array $data
     * @param bool $success
     * @param string $message
     *
     * @return string
     */
    public function getJson(array $data = [], bool $success = true, string $message = '')
    {
        return json_encode([
            'data'      => $data,
            'success'   => $success,
            'message'   => $message,
        ]);
    }

    /**
     * Получение разметки страницы с описанием ошибки
     *
     * @param string $message
     * @param string $backUrl
     *
     * @return string
     */
    public function getErrorView(string $message, string $backUrl)
    {
        return $this->getView('error/custom', [
            'message'   => $message,
            'url'       => $backUrl,
        ]);
    }

    /**
     * Получение json-строки с описанием ошибки
     *
     * @param string $message
     *
     * @return string
     */
    public function getErrorJson(string $message = '')
    {
        return $this->getJson([], false, $message);
    }

    /**
     * Получение параметра из конфига по ключу
     *
     * @param string $key
     *
     * @return mixed
     */
    public function getConfigParam(string $key)
    {
        return $this->config->get($key);
    }

    /**
     * Получение объекта DBService
     *
     * @return DBService
     */
    public function getDBService()
    {
        return $this->db;
    }

    /**
     * Получение объекта NewsService
     *
     * @return NewsService
     */
    public function getNewsService()
    {
        return $this->news;
    }

    /**
     * Получение объекта PDO
     *
     * @return \PDO
     */
    public function getPDO()
    {
        return $this->db->getPDO();
    }
}