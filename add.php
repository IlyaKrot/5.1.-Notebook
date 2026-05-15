<?php
// Модуль добавления новой записи. Напрямую в браузере не используется.
require_once __DIR__ . '/db.php';

$message = '';
$row = array(
    'surname'  => '',
    'name'     => '',
    'lastname' => '',
    'gender'   => '',
    'date'     => '',
    'phone'    => '',
    'location' => '',
    'email'    => '',
    'comment'  => '',
);
$button = 'Добавить';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = get_db();

    $surname  = trim($_POST['surname']  ?? '');
    $name     = trim($_POST['name']     ?? '');
    $lastname = trim($_POST['lastname'] ?? '');
    $gender   = trim($_POST['gender']   ?? '');
    $date     = trim($_POST['date']     ?? '');
    $phone    = trim($_POST['phone']    ?? '');
    $location = trim($_POST['location'] ?? '');
    $email    = trim($_POST['email']    ?? '');
    $comment  = trim($_POST['comment']  ?? '');

    if ($surname === '' || $name === '') {
        $message = '<p class="error">Ошибка: запись не добавлена</p>';
    } else {
        if ($date === '') {
            $date = null;
        }
        $stmt = $db->prepare(
            'INSERT INTO contacts
            (surname, name, lastname, gender, birth_date, phone, location, email, comment)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $ok = $stmt->execute(array(
            $surname, $name, $lastname, $gender, $date,
            $phone, $location, $email, $comment
        ));

        if ($ok) {
            $message = '<p class="success">Запись добавлена</p>';
        } else {
            $message = '<p class="error">Ошибка: запись не добавлена</p>';
        }
    }
}

echo $message;
?>
<form name="form_add" method="post">
    <div class="column">
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
