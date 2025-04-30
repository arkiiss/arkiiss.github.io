<?php
require_once 'auth.php';

if (isLoggedIn()) {
    header('Location: admin.php');
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (login($_POST['login'], $_POST['password'])) {
        header('Location: admin.php');
        exit();
    } else {
        $error = 'Неверный логин или пароль';
    }
}
?>

<form method="POST">
    <h2>Вход</h2>
    <?php if ($error): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>
    
    <label>Логин: <input type="text" name="login" required></label>
    <label>Пароль: <input type="password" name="password" required></label>
    
    <button type="submit">Войти</button>
</form>