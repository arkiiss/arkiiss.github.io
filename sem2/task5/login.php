<?php
header('Content-Type: text/html; charset=UTF-8');
session_start();

if (!empty($_SESSION['login'])) {
    header('Location: index.php');
    exit();
}

require_once 'database.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = $_POST['login'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $stmt = $db->prepare("SELECT id, password FROM users WHERE login = ?");
    $stmt->execute([$login]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['login'] = $login;
        $_SESSION['user_id'] = $user['id'];
        
        // Получаем form_id для пользователя
        $stmt = $db->prepare("SELECT id FROM form_data WHERE user_id = ?");
        $stmt->execute([$user['id']]);
        $_SESSION['form_id'] = $stmt->fetchColumn();
        
        header('Location: index.php');
        exit();
    } else {
        $error = 'Неверный логин или пароль';
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Вход</title>
</head>
<body>
    <form method="POST">
        <?php if ($error): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>
        <input type="text" name="login" placeholder="Логин" required>
        <input type="password" name="password" placeholder="Пароль" required>
        <button type="submit">Войти</button>
    </form>
</body>
</html>