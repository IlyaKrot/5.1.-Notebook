<?php
// Модуль редактирования существующей записи. Напрямую в браузере не используется.
require_once __DIR__ . '/db.php';

$db = get_db();
$message = '';
$button = 'Сохранить';

// Сохранение изменений.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id       = (int) ($_POST['id'] ?? 0);
    $surname  = trim($_POST['surname']  ?? '');
    $name     = trim($_POST['name']     ?? '');
    $lastname = trim($_POST['lastname'] ?? '');
    $gender   = trim($_POST['gender']   ?? '');
    $date     = trim($_POST['date']     ?? '');
    $phone    = trim($_POST['phone']    ?? '');
    $location = trim($_POST['location'] ?? '');
    $email    = trim($_POST['email']    ?? '');
    $comment  = trim($_POST['comment']  ?? '');

    if ($id > 0 && $surname !== '' && $name !== '') {
        if ($date === '') {
            $date = null;
        }
        $stmt = $db->prepare(
            'UPDATE contacts SET
            surname = ?, name = ?, lastname = ?, gender = ?, birth_date = ?,
            phone = ?, location = ?, email = ?, comment = ?
            WHERE id = ?'
        );
        $ok = $stmt->execute(array(
            $surname, $name, $lastname, $gender, $date,
            $phone, $location, $email, $comment, $id
        ));
        if ($ok) {
            $message = '<p class="success">Запись обновлена</p>';
        } else {
            $message = '<p class="error">Ошибка: запись не обновлена</p>';
        }
    } else {
        $message = '<p class="error">Ошибка: запись не обновлена</p>';
    }
    $_GET['edit_id'] = $id;
}

// Список записей для выбора (сортировка по фамилии, затем по имени).
$rows = $db->query('SELECT id, surname, name FROM contacts ORDER BY surname ASC, name ASC')
    ->fetchAll();

if (count($rows) === 0) {
    echo '<p>В записной книжке пока нет ни одной записи для редактирования.</p>';
    return;
}

$ids = array();
foreach ($rows as $r) {
    $ids[] = (int) $r['id'];
}

// Текущая запись: выбранная пользователем либо первая по порядку.
$current = isset($_GET['edit_id']) ? (int) $_GET['edit_id'] : 0;
if (!in_array($current, $ids, true)) {
    $current = $ids[0];
}

// Список ссылок с подсветкой текущей.
echo '<div class="submenu" style="flex-wrap:wrap;">';
foreach ($rows as $r) {
    $cls = ((int) $r['id'] === $current) ? ' class="select"' : '';
    echo '<a' . $cls . ' href="index.php?page=edit&edit_id=' . (int) $r['id'] . '">'
        . htmlspecialchars($r['surname'] . ' ' . $r['name']) . '</a>';
}
echo '</div>';

echo $message;

// Данные выбранной записи.
$stmt = $db->prepare('SELECT * FROM contacts WHERE id = ?');
$stmt->execute(array($current));
$db_row = $stmt->fetch();

$row = array(
    'surname'  => $db_row['surname'],
    'name'     => $db_row['name'],
    'lastname' => $db_row['lastname'],
    'gender'   => $db_row['gender'],
    'date'     => $db_row['birth_date'],
    'phone'    => $db_row['phone'],
    'location' => $db_row['location'],
    'email'    => $db_row['email'],
    'comment'  => $db_row['comment'],
);
?>
<form name="form_edit" method="post">
    <div class="column">
        <input type="hidden" name="id" value="<?= (int) $current; ?>">
        <div class="add">
            <label>Фамилия</label> <input type="text" name="surname" placeholder="Фамилия" value="<?= htmlspecialchars($row['surname']); ?>">
        </div>
        <div class="add">
            <label>Имя</label> <input type="text" name="name" placeholder="Имя" value="<?= htmlspecialchars($row['name']); ?>">
        </div>
        <div class="add">
            <label>Отчество</label> <input type="text" name="lastname" placeholder="Отчество" value="<?= htmlspecialchars($row['lastname']); ?>">
        </div>
        <div class="add">
            <label>Пол</label>
            <select name="gender">
                <option value="">— не выбрано —</option>
                <option value="мужской"<?= $row['gender'] === 'мужской' ? ' selected' : ''; ?>>мужской</option>
                <option value="женский"<?= $row['gender'] === 'женский' ? ' selected' : ''; ?>>женский</option>
            </select>
        </div>
        <div class="add">
            <label>Дата рождения</label> <input type="date" name="date" value="<?= htmlspecialchars($row['date']); ?>">
        </div>
        <div class="add">
            <label>Телефон</label> <input type="text" name="phone" placeholder="Телефон" value="<?= htmlspecialchars($row['phone']); ?>">
        </div>
        <div class="add">
            <label>Адрес</label> <input type="text" name="location" placeholder="Адрес" value="<?= htmlspecialchars($row['location']); ?>">
        </div>
        <div class="add">
            <label>Email</label> <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($row['email']); ?>">
        </div>
        <div class="add">
            <label>Комментарий</label> <textarea name="comment" placeholder="Краткий комментарий"><?= htmlspecialchars($row['comment']); ?></textarea>
        </div>

        <button type="submit" value="<?= $button; ?>" name="button" class="form-btn"><?= $button; ?></button>
    </div>
</form>
