<?php
header("Content-Type: text/html; charset=UTF-8");
session_start();

require_once 'db.php';
require_once 'auth.php';

$error = false;
$log = !empty($_SESSION['login']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['logout_form'])) {
        // Очистка куки и сессии
        $cookies = ['fio', 'number', 'email', 'date', 'radio', 'language', 'bio', 'check'];
        foreach ($cookies as $cookie) {
            setcookie($cookie.'_value', '', time() - 3600);
            setcookie($cookie.'_error', '', time() - 3600);
        }
        session_destroy();
        header('Location: ./');
        exit();
    }

    // Получение данных формы
    $fio = $_POST['fio'] ?? '';
    $number = preg_replace('/\D/', '', $_POST['number'] ?? '');
    $email = $_POST['email'] ?? '';
    $date = $_POST['date'] ?? '';
    $radio = $_POST['radio'] ?? '';
    $language = $_POST['language'] ?? [];
    $bio = $_POST['bio'] ?? '';
    $check = $_POST['check'] ?? '';

    // Валидация
    function check_field($name, $value, $validation) {
        global $error;
        if ($validation($value)) {
            setcookie($name.'_error', 'Ошибка в поле '.$name, time() + 3600);
            $error = true;
        }
        setcookie($name.'_value', is_array($value) ? implode(',', $value) : $value, time() + 3600);
    }

    // Проверки для каждого поля
    check_field('fio', $fio, fn($v) => empty($v) || !preg_match('/^[а-яёА-ЯЁ\s\-]+$/u', $v));
    check_field('number', $number, fn($v) => empty($v) || !preg_match('/^\d{11}$/', $v));
    // ... другие проверки

    if (!$error) {
        try {
            if ($log) {
                // Редактирование существующей записи
                $stmt = $db->prepare("UPDATE form_data SET fio=?, number=?, email=?, dat=?, radio=?, bio=? WHERE user_id=?");
                $stmt->execute([$fio, $number, $email, $date, $radio, $bio, $_SESSION['user_id']]);
                
                // Обновление языков
                $db->prepare("DELETE FROM form_data_lang WHERE id_form=?")->execute([$_SESSION['form_id']]);
                $stmt = $db->prepare("INSERT INTO form_data_lang (id_form, id_lang) VALUES (?, ?)");
                foreach ($language as $lang_id) {
                    $stmt->execute([$_SESSION['form_id'], $lang_id]);
                }
            } else {
                // Новая запись
                $login = generateLogin($fio);
                $password = generatePassword();
                $password_hash = password_hash($password, PASSWORD_DEFAULT);

                // Сохранение пользователя
                $stmt = $db->prepare("INSERT INTO users (login, password) VALUES (?, ?)");
                $stmt->execute([$login, $password_hash]);
                $user_id = $db->lastInsertId();

                // Сохранение формы
                $stmt = $db->prepare("INSERT INTO form_data (user_id, fio, number, email, dat, radio, bio) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$user_id, $fio, $number, $email, $date, $radio, $bio]);
                $form_id = $db->lastInsertId();

                // Сохранение языков
                $stmt = $db->prepare("INSERT INTO form_data_lang (id_form, id_lang) VALUES (?, ?)");
                foreach ($language as $lang_id) {
                    $stmt->execute([$form_id, $lang_id]);
                }

                // Показ данных для входа
                setcookie('login', $login, time() + 3600);
                setcookie('pass', $password, time() + 3600);
            }

            setcookie('save', '1', time() + 3600);
            header('Location: index.php');
            exit();
        } catch (PDOException $e) {
            die('Ошибка базы данных: ' . $e->getMessage());
        }
    } else {
        header('Location: index.php');
        exit();
    }
} else {
    // Отображение формы
    include 'form.php';
}