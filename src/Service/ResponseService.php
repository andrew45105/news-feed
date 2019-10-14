<?php

namespace App\Service;

/**
 * Class ResponseService
 * @package App\Service
 */
class ResponseService
{
    /**
     * Получить контент шаблона страницы
     *
     * @param string $name
     * @param array|null $args
     *
     * @return string
     */
    public function getView(string $name, $args = null)
    {
        ob_start();
        $tpl = dirname(__FILE__) . "/../Resources/views/{$name}.php";

        if (file_exists($tpl)) {
            if ($args) {
                extract($args);
            }
            include $tpl;
        } else {
            echo "Шаблон {$name} не найден";
        }
        return ob_get_clean();
    }
}