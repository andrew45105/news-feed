<?php

namespace App\Controller;

/**
 * Class MainController
 * @package App\Controller
 */
class MainController extends BaseController
{
    /**
     * Маршрут GET /
     */
    public function getIndex()
    {
        $perPage = intval($this->getConfigParam('news')['per_page']);

        return $this->getView('main', [
            'title' => 'Новостная лента',
            'news'  => $this->getDBService()->getNews($perPage, 0),
            'pages' => $this->getNewsService()->getPagesCount(),
        ]);
    }
}