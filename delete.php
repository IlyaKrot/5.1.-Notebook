<?php
// Модуль удаления записи. Напрямую в браузере не используется.
require_once __DIR__ . '/db.php';

$db = get_db();

// Удаление выбранной записи.
if (isset($_GET['del_id'])) {
    $id = (int) $_GET['del_id'];

    $stmt = $db->prepare('SELECT surname FROM contacts WHERE id = ?');
    $stmt->execute(array($id));
    $found = $stmt->fetch();

    if ($found) {
        $del = $db->prepare('DELETE FROM contacts WHERE id = ?');
        $del->execute(array($id));
        echo '<p class="success">Запись с фамилией '
            . htmlspecialchars($found['surname']) . ' удалена</p>';
    } else {
        echo '<p class="error">Ошибка: запись не найдена</p>';
    }
}

// Список оставшихся записей (фамилия + инициалы).
$rows = $db->query('SELECT id, surname, name, lastname FROM contacts ORDER BY surname ASC, name ASC')
    ->fetchAll();

if (count($rows) === 0) {
    echo '<p>В записной книжке нет записей для удаления.</p>';
    return;
}

echo '<div class="submenu" style="flex-wrap:wrap;">';
foreach ($rows as $r) {
    $initials = '';
    if ($r['name'] !== '') {
        $initials .= ' ' . mb_substr($r['name'], 0, 1) . '.';
    }
    if ($r['lastname'] !== '') {
        $initials .= ' ' . mb_substr($r['lastname'], 0, 1) . '.';
    }
    echo '<a href="index.php?page=delete&del_id=' . (int) $r['id'] . '">'
        . htmlspecialchars($r['surname'] . $initials) . '</a>';
}
echo '</div>';
