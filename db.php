<?php
// Модуль подключения к базе данных (SQLite через PDO).
// Напрямую в браузере не используется.

function get_db()
{
    static $db = null;
    if ($db !== null) {
        return $db;
    }

    $file = __DIR__ . '/notebook.sqlite';
    $db = new PDO('sqlite:' . $file);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $db->exec("CREATE TABLE IF NOT EXISTS contacts (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        surname TEXT NOT NULL,
        name TEXT NOT NULL,
        lastname TEXT DEFAULT '',
        gender TEXT DEFAULT '',
        birth_date TEXT,
        phone TEXT DEFAULT '',
        location TEXT DEFAULT '',
        email TEXT DEFAULT '',
        comment TEXT DEFAULT '',
        created_at TEXT DEFAULT CURRENT_TIMESTAMP
    )");

    return $db;
}
