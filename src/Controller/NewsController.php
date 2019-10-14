<?php

namespace App\Controller;

use App\Exception\ApiException;

/**
 * Class NewsController
 * @package App\Controller
 */
class NewsController extends BaseController
{
    /**
     * Маршрут GET /news/list/{$page}
     */
    public function getList($page)
    {
        $perPage    = intval($this->getConfigParam('news')['per_page']);
        $offset     = ($page - 1) * $perPage;
        return $this->getJson([
            'news' => $this->getDBService()->getNews($perPage, $offset),
        ]);
    }

    /**
     * Маршрут GET /news/pages
     */
    public function getPages()
    {
        return $this->getJson([
            'count' => $this->getNewsService()->getPagesCount(),
        ]);
    }

    /**
     * Маршрут POST /news/create
     */
    public function postCreate()
    {
        $title  = trim(strip_tags($_POST['title']));
        $body   = trim(strip_tags($_POST['body']));

        $this->getNewsService()->checkFields($title, $body);
        $db = $this->getDBService();

        if ($id = $db->addNews($title, $body)){
            return $this->getJson([
                'id'    => $id,
                'title' => $title,
                'body'  => $body,
            ]);
        } else {
            throw new ApiException('Внутренняя ошибка');
        }
    }

    /**
     * Маршрут POST /news/edit/{id}
     */
    public function postEdit($id)
    {
        $id     = intval(trim(strip_tags($id)));
        $title  = trim(strip_tags($_POST['title']));
        $body   = trim(strip_tags($_POST['body']));

        $db = $this->getDBService();

        if (!$db->getNewsById($id)){
            throw new ApiException("Новость с id = $id не найдена");
        }

        $this->getNewsService()->checkFields($title, $body);

        if ($db->editNews($id, $title, $body)){
            return $this->getJson([
                'id'    => $id,
                'title' => $title,
                'body'  => $body
            ]);
        } else {
            throw new ApiException('Внутренняя ошибка');
        }
    }

    /**
     * Маршрут DELETE /news/{id}
     */
    public function deleteIndex($id)
    {
        $id = intval(trim(strip_tags($id)));

        $db = $this->getDBService();

        if (!$db->getNewsById($id)){
            throw new ApiException("Новость с id = $id не найдена");
        }

        if ($db->deleteNews($id)){
            return $this->getJson();
        } else {
            throw new ApiException('Внутренняя ошибка');
        }
    }
}