<?php
// Модуль вывода содержимого базы данных. Напрямую в браузере не используется.
require_once __DIR__ . '/db.php';

// Возвращает строку с HTML-кодом таблицы и пагинации.
// $sort — тип сортировки (id|surname|date), $page — номер страницы пагинации.
function get_viewer($sort = 'id', $page = 1)
{
    $db = get_db();

    $columns = array(
        'id'      => 'id',
        'surname' => 'surname',
        'date'    => 'birth_date',
    );
    $order = isset($columns[$sort]) ? $columns[$sort] : 'id';
    if (!isset($columns[$sort])) {
        $sort = 'id';
    }

    $per_page = 10;
    $total = (int) $db->query('SELECT COUNT(*) AS c FROM contacts')->fetch()['c'];
    $pages = max(1, (int) ceil($total / $per_page));

    $page = (int) $page;
    if ($page < 1) {
        $page = 1;
    }
    if ($page > $pages) {
        $page = $pages;
    }
    $offset = ($page - 1) * $per_page;

    $res = $db->query("SELECT * FROM contacts ORDER BY $order ASC LIMIT $per_page OFFSET $offset");

    if ($total === 0) {
        return '<p>В записной книжке пока нет ни одной записи.</p>';
    }

    $html = '<table>';
    $html .= '<tr>'
        . '<td>Фамилия</td><td>Имя</td><td>Отчество</td><td>Пол</td>'
        . '<td>Дата рождения</td><td>Телефон</td><td>Адрес</td>'
        . '<td>Email</td><td>Комментарий</td>'
        . '</tr>';

    foreach ($res as $row) {
        $html .= '<tr>'
            . '<td>' . htmlspecialchars($row['surname']) . '</td>'
            . '<td>' . htmlspecialchars($row['name']) . '</td>'
            . '<td>' . htmlspecialchars($row['lastname']) . '</td>'
            . '<td>' . htmlspecialchars($row['gender']) . '</td>'
            . '<td>' . htmlspecialchars($row['birth_date']) . '</td>'
            . '<td>' . htmlspecialchars($row['phone']) . '</td>'
            . '<td>' . htmlspecialchars($row['location']) . '</td>'
            . '<td>' . htmlspecialchars($row['email']) . '</td>'
            . '<td>' . htmlspecialchars($row['comment']) . '</td>'
            . '</tr>';
    }
    $html .= '</table>';

    // Пагинация выводится только если записей больше одной страницы.
    if ($pages > 1) {
        $html .= '<div class="submenu pagination">';
        for ($i = 1; $i <= $pages; $i++) {
            $cls = ($i === $page) ? ' class="select"' : '';
            $html .= '<a' . $cls . ' href="index.php?page=view&sort=' . $sort
                . '&p=' . $i . '">' . $i . '</a>';
        }
        $html .= '</div>';
    }

    return $html;
}
