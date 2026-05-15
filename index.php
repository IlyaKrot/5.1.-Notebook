<?php
// Единственный загружаемый в браузер документ.
require_once __DIR__ . '/menu.php';
require_once __DIR__ . '/viewer.php';

$page = isset($_GET['page']) ? $_GET['page'] : 'view';
$allowed = array('view', 'add', 'edit', 'delete');
if (!in_array($page, $allowed, true)) {
    $page = 'view';
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Записная книжка</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?= get_menu(); ?>
    <main>
        <?php
        switch ($page) {
            case 'add':
                require __DIR__ . '/add.php';
                break;

            case 'edit':
                require __DIR__ . '/edit.php';
                break;

            case 'delete':
                require __DIR__ . '/delete.php';
                break;

            case 'view':
            default:
                $sort = isset($_GET['sort']) ? $_GET['sort'] : 'id';
                $p = isset($_GET['p']) ? (int) $_GET['p'] : 1;
                echo get_viewer($sort, $p);
                break;
        }
        ?>
    </main>
    <footer></footer>
</body>
</html>
