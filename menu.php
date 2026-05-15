<?php
// Модуль формирования меню сайта. Напрямую в браузере не используется.

// Возвращает строку с HTML-кодом основного меню (и подменю сортировки
// для пункта "Просмотр"). Функция без параметров — состояние берётся из $_GET.
function get_menu()
{
    $page = isset($_GET['page']) ? $_GET['page'] : 'view';
    $items = array(
        'view'   => 'Просмотр',
        'add'    => 'Добавление записи',
        'edit'   => 'Редактирование записи',
        'delete' => 'Удаление записи',
    );
    // Один пункт всегда активен, вне зависимости от параметров.
    if (!isset($items[$page])) {
        $page = 'view';
    }

    $html = '<header>';
    foreach ($items as $key => $label) {
        $cls = ($key === $page) ? ' class="select"' : '';
        $html .= '<a' . $cls . ' href="index.php?page=' . $key . '">' . $label . '</a>';
    }
    $html .= '</header>';

    // Дополнительное меню сортировки — только на странице просмотра.
    if ($page === 'view') {
        $sorts = array(
            'id'      => 'По порядку добавления',
            'surname' => 'По фамилии',
            'date'    => 'По дате рождения',
        );
        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'id';
        if (!isset($sorts[$sort])) {
            $sort = 'id';
        }

        $html .= '<div class="submenu">';
        foreach ($sorts as $key => $label) {
            $cls = ($key === $sort) ? ' class="select"' : '';
            $html .= '<a' . $cls . ' href="index.php?page=view&sort=' . $key . '">' . $label . '</a>';
        }
        $html .= '</div>';
    }

    return $html;
}
