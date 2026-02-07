<?php
include("../config/db.php");

if ($_POST) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    // Usar prepared statement para prevenir SQL Injection
    $stmt = $conn->prepare("SELECT id, nombre, password FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $nombre, $hashed_password);
        $stmt->fetch();
} elseif (!isset($users[$username]['password_hash']) || !is_string($users[$username]['password_hash'])) {
        $errors[] = 'Usuario o contrasena invalidos.';
            
            // Redirigir a la página solicitada o al dashboard
            $redirect = isset($_SESSION['redirect_url']) ? $_SESSION['redirect_url'] : '../dashboard/';
            unset($_SESSION['redirect_url']);
            header("Location: $redirect");
            exit();
        } else {
            $error = "Contraseña incorrecta";
        }
    } else {
        $error = "Usuario no encontrado";
    }
    $stmt->close();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Repuestos</title>
    <link rel="stylesheet" href="/assets/css/dark.css">
</head>
<body>
    <div class="container">
        <h2>🔐 Iniciar Sesión</h2>
        
        <?php if (isset($error)): ?>
            <div class="card" style="background: rgba(224, 108, 117, 0.1); border-color: var(--danger);">
                ❌ <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <form method='POST'>
            <label class="muted">Email</label>
            <input type='email' name='email' placeholder='tu@email.com' required>
            
            <label class="muted">Contraseña</label>
            <input type='password' name='password' placeholder='••••••' required>
            
            <button type='submit'>Entrar</button>
        </form>
        
        <p style="margin-top: 20px;" class="muted">
            ¿No tienes cuenta? <a href='register.php'>Regístrate aquí</a><br>
            <a href='../index.php'>← Volver al inicio</a>
        </p>
    </div>
</body>
</html>