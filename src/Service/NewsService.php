<?php

namespace App\Service;

use App\Exception\ApiException;

/**
 * Class NewsService
 * @package App\Service
 */
class NewsService
{
    /**
     * @var int
     */
    private $postsPerPage;

    /**
     * @var array
     */
    private $sizes;

    /**
     * @var DBService
     */
    private $db;

    /**
     * NewsService constructor.
     * @param ConfigService $config
     * @param DBService $db
     */
    public function __construct(ConfigService $config, DBService $db)
    {
        $newsConfig         = $config->get('news');
        $this->postsPerPage = intval($newsConfig['per_page']);
        $this->sizes        = $newsConfig['size'];
        $this->db           = $db;
    }

    /**
     * Проверка полей новости на длину
     *
     * @param $title
     * @param $body
     *
     * @throws ApiException
     */
    public function checkFields($title, $body)
    {
        if (!$title || !$body) {
            throw new ApiException('Не указан заголовок или текст');
        }

        if (mb_strlen($title) < $this->sizes['title_min']) {
            throw new ApiException("Заголовок слишком короткий (меньше {$this->sizes['title_min']} символов)");
        }
        if (mb_strlen($body) < $this->sizes['body_min']) {
            throw new ApiException("Текст слишком короткий (меньше {$this->sizes['body_min']} символов)");
        }
        if (mb_strlen($title) > $this->sizes['title_max']) {
            throw new ApiException("Заголовок слишком длинный (больше {$this->sizes['title_max']} символов)");
        }
        if (mb_strlen($body) > $this->sizes['body_max']) {
            throw new ApiException("Текст слишком длинный (больше {$this->sizes['body_max']} символов)");
        }
    }

    /**
     * Получить кол-во страниц с новостями
     *
     * @return int
     */
    public function getPagesCount()
    {
        $newsCount  = $this->db->getNewsCount();
        $perPage    = $this->postsPerPage;
        if ($newsCount % $perPage === 0) {
            $pagesCount = $newsCount / $perPage;
        } else {
            $pagesCount = intdiv($newsCount, $perPage) + 1;
        };
        $pagesCount = $pagesCount ? $pagesCount : 1;
        return $pagesCount;
    }
}