<?php

namespace App\Service;

/**
 * Class DBService
 * @package Service
 */
class DBService
{
    /**
     * @var \PDO
     */
    private $pdo;

    /**
     * DBService constructor.
     * @param ConfigService $config
     */
    public function __construct(ConfigService $config)
    {
        $dbHost     = $config->get('database_host');
        $dbName     = $config->get('database_name');
        $dbPort     = $config->get('database_port');
        $dbUser     = $config->get('database_user');
        $dbPassword = $config->get('database_password');

        $this->pdo = new \PDO("mysql:host=$dbHost;port=$dbPort;dbname=$dbName", $dbUser, $dbPassword);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->pdo->query('SET NAMES utf8');
    }

    /**
     * @return \PDO
     */
    public function getPDO()
    {
        return $this->pdo;
    }

    /**
     * Получение списка всех новостей
     *
     * @param int $limit
     * @param int $offset
     *
     * @return array
     */
    public function getNews(int $limit, int $offset)
    {
        $stmt = $this->pdo->prepare("SELECT id, title, body FROM news ORDER BY id DESC LIMIT :lim OFFSET :off");
        $stmt->bindValue(':lim', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':off', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Получение количества всех новостей
     *
     * @return int
     */
    public function getNewsCount()
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(id) FROM news");
        $stmt->execute();
        return intval($stmt->fetchColumn());
    }

    /**
     * Получение новости по id
     *
     * @param int $id
     *
     * @return array|null
     */
    public function getNewsById(int $id)
    {
        $stmt = $this->pdo->prepare("SELECT id, title, body FROM news WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetchAll();

        return count($result) == 1 ? $result[0] : null;
    }

    /**
     * Добавление новости
     *
     * @param string $title
     * @param string $body
     *
     * @return int|false
     */
    public function addNews(string $title, string $body)
    {
        $stmt = $this->pdo->prepare("INSERT INTO news(title, body) VALUES (:title, :body)");
        $result = $stmt->execute([
            ':title'    => $title,
            ':body'     => $body
        ]);
        return $result ? $this->pdo->lastInsertId() : false;
    }

    /**
     * Редактирование новости
     *
     * @param int $id
     * @param string $title
     * @param string $body
     *
     * @return bool
     */
    public function editNews(int $id, string $title, string $body)
    {
        $stmt = $this->pdo->prepare("UPDATE news SET title = :title, body = :body WHERE id = :id");
        return $stmt->execute([
            ':title'    => $title,
            ':body'     => $body,
            ':id'       => $id,
        ]);
    }

    /**
     * Удаление новости
     *
     * @param int $id
     *
     * @return bool
     */
    public function deleteNews(int $id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM news WHERE id = :id");
        return $stmt->execute([
            ':id' => $id,
        ]);
    }
}