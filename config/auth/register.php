<?php
declare(strict_types=1);

require_once __DIR__ . '/../db.php';

session_start();

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim((string)($_POST['username'] ?? ''));
    $name = trim((string)($_POST['name'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    $password2 = (string)($_POST['password2'] ?? '');

    if ($username === '' || $name === '' || $password === '') {
        $errors[] = 'Todos los campos son obligatorios.';
    } elseif ($password !== $password2) {
        $errors[] = 'Las contrasenas no coinciden.';
    } else {
        $users = db_get_users();
        if (isset($users[$username])) {
            $errors[] = 'El usuario ya existe.';
        } else {
            $users[$username] = [
                'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                'name' => $name,
            ];
            db_save_users($users);
            $success = true;
        }
    }
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f6f7fb; margin: 0; }
        .wrap { max-width: 420px; margin: 80px auto; padding: 24px; background: #fff; border-radius: 8px; box-shadow: 0 8px 24px rgba(0,0,0,.08); }
        h1 { margin: 0 0 16px; font-size: 20px; }
        label { display: block; margin-top: 12px; font-size: 14px; }
        input { width: 100%; padding: 10px 12px; margin-top: 6px; border: 1px solid #ccc; border-radius: 6px; }
        button { margin-top: 16px; width: 100%; padding: 10px 12px; border: 0; border-radius: 6px; background: #1f6feb; color: #fff; font-weight: 600; cursor: pointer; }
        .error { background: #fff4f4; border: 1px solid #f0b3b3; color: #8a1f1f; padding: 10px 12px; border-radius: 6px; margin-bottom: 12px; }
        .success { background: #f0fff4; border: 1px solid #b3f0c2; color: #1f8a3f; padding: 10px 12px; border-radius: 6px; margin-bottom: 12px; }
        .hint { margin-top: 10px; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="wrap">
        <h1>Registro</h1>
        <?php foreach ($errors as $error): ?>
            <div class="error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endforeach; ?>
        <?php if ($success): ?>
            <div class="success">Usuario creado. Ya puedes iniciar sesion.</div>
        <?php endif; ?>
        <form method="post">
            <label for="name">Nombre</label>
            <input id="name" name="name" type="text" required>
            <label for="username">Usuario</label>
            <input id="username" name="username" type="text" autocomplete="username" required>
            <label for="password">Contrasena</label>
            <input id="password" name="password" type="password" autocomplete="new-password" required>
            <label for="password2">Repite contrasena</label>
            <input id="password2" name="password2" type="password" autocomplete="new-password" required>
            <button type="submit">Crear cuenta</button>
        </form>
        <div class="hint"><a href="/config/auth/login.php">Volver al login</a></div>
    </div>
</body>
</html>
