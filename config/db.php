<?php
declare(strict_types=1);

// Very small JSON-based storage for users.
// Replace with a real DB connection when available.

function db_users_path(): string
{
    $dir = __DIR__ . '/../data';
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
    return $dir . '/users.json';
}

function db_get_users(): array
{
    $path = db_users_path();
    if (!file_exists($path)) {
        $default = [
            'admin' => [
                'password_hash' => password_hash('admin123', PASSWORD_DEFAULT),
                'name' => 'Administrador',
            ],
        ];
        db_save_users($default);
        return $default;
    }

    $raw = file_get_contents($path);
    $data = json_decode($raw ?: '[]', true);
    if (!is_array($data)) {
        return [];
    }
    return $data;
}

function db_save_users(array $users): void
{
    $path = db_users_path();
    file_put_contents($path, json_encode($users, JSON_PRETTY_PRINT));
}
