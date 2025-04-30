<?php
header('Content-Type: text/html; charset=UTF-8');
session_start();
require_once 'auth.php';
require_once 'db.php';
// Проверка аутентификации
if (isLoggedIn()) {
    header('Location: admin.php');
    exit();
}
// Генерация логина и пароля
$login = generateLogin($values['fullName']);
$plain_password = generatePassword();
$password_hash = password_hash($plain_password, PASSWORD_DEFAULT);

// Сохраняем пользователя в БД
$stmt = $database->prepare("INSERT INTO users (submission_id, login, password_hash) VALUES (?, ?, ?)");
$stmt->execute([$submissionId, $login, $password_hash]);

// Показываем пользователю его credentials
echo "<div class='credentials'>
        <h3>Ваши данные для входа:</h3>
        <p><strong>Логин:</strong> $login</p>
        <p><strong>Пароль:</strong> $plain_password</p>
        <p>Сохраните эти данные для редактирования вашей анкеты</p>
      </div>";
      
$errors = [];
$values = [
    'fullName' => '',
    'phoneNumber' => '',
    'userEmail' => '',
    'eventDate' => '',
    'gender' => '',
    'selectedLanguages' => [],
    'userBio' => '',
    'agreement' => ''
];

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Чтение ошибок из куки
    foreach ($values as $field => &$value) {
        if (isset($_COOKIE['error_' . $field])) {
            $errors[$field] = $_COOKIE['error_' . $field];
            setcookie('error_' . $field, '', time() - 3600);
        }
        if (isset($_COOKIE['value_' . $field])) {
            $value = is_array(json_decode($_COOKIE['value_' . $field], true))
                ? json_decode($_COOKIE['value_' . $field], true)
                : $_COOKIE['value_' . $field];
        }
    }

    if (!empty($_GET['submit'])) {
        echo "<p style='color: green;'>Спасибо, ваши данные успешно сохранены.</p>";
    }

    include('contact_form.php');
    exit();
}

// Обработка POST данных
$values = [
    'fullName' => $_POST['fullName'] ?? '',
    'phoneNumber' => preg_replace('/\D/', '', $_POST['phoneNumber'] ?? ''),
    'userEmail' => $_POST['userEmail'] ?? '',
    'eventDate' => $_POST['eventDate'] ?? '',
    'gender' => $_POST['gender'] ?? '',
    'selectedLanguages' => $_POST['selectedLanguages'] ?? [],
    'userBio' => $_POST['userBio'] ?? '',
    'agreement' => $_POST['agreement'] ?? ''
];

// Валидация
if (!preg_match('/^[а-яА-ЯёЁ\s]+$/u', $values['fullName']) || strlen($values['fullName']) > 255) {
    $errors['fullName'] = 'ФИО должно содержать только кириллические буквы и пробелы (макс. 255 символов)';
}

if (!preg_match('/^\d{11}$/', $values['phoneNumber'])) {
    $errors['phoneNumber'] = 'Номер телефона должен содержать 11 цифр.';
}

if (!filter_var($values['userEmail'], FILTER_VALIDATE_EMAIL) || strlen($values['userEmail']) > 255) {
    $errors['userEmail'] = 'Укажите корректный e-mail (макс. 255 символов).';
}

if (empty($values['eventDate'])) {
    $errors['eventDate'] = 'Дата обязательна для заполнения.';
}

if (empty($values['gender'])) {
    $errors['gender'] = 'Выберите пол.';
}

if (empty($values['selectedLanguages']) || !is_array($values['selectedLanguages'])) {
    $errors['selectedLanguages'] = 'Выберите хотя бы один язык программирования.';
}

if (strlen($values['userBio']) > 65535) {
    $errors['userBio'] = 'Биография слишком длинная.';
}

if (empty($values['agreement'])) {
    $errors['agreement'] = 'Вы должны согласиться с условиями.';
}

if (!empty($errors)) {
    // Сохраняем данные и ошибки в куки
    foreach ($values as $key => $value) {
        setcookie('value_' . $key, is_array($value) ? json_encode($value) : $value, time() + 3600, '/');
    }
    foreach ($errors as $key => $error) {
        setcookie('error_' . $key, $error, time() + 3600, '/');
    }
    header('Location: index.php');
    exit();
}

// Сохраняем значения на 1 год
foreach ($values as $key => $value) {
    setcookie('value_' . $key, is_array($value) ? json_encode($value) : $value, time() + 365 * 24 * 60 * 60, '/');
}

// Подключение к БД и сохранение данных
try {
    $dbUser = 'u68788'; 
    $dbPass = '9724771'; 
    $database = new PDO('mysql:host=localhost;dbname=u68788', $dbUser, $dbPass,
        [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); 

    $placeholders = implode(',', array_fill(0, count($values['selectedLanguages']), '?'));
    $languageQuery = $database->prepare("SELECT id, name FROM programming_languages WHERE name IN ($placeholders)");
    foreach ($values['selectedLanguages'] as $key => $val) {
        $languageQuery->bindValue($key + 1, $val);
    }
    $languageQuery->execute();
    $languages = $languageQuery->fetchAll(PDO::FETCH_ASSOC);

    $insertData = $database->prepare("INSERT INTO user_submissions (fullName, phoneNumber, userEmail, eventDate, gender, userBio) VALUES (?, ?, ?, ?, ?, ?)");
    $insertData->execute([$values['fullName'], $values['phoneNumber'], $values['userEmail'], $values['eventDate'], $values['gender'], $values['userBio']]);
    $submissionId = $database->lastInsertId();

    $insertLang = $database->prepare("INSERT INTO submission_languages (submission_id, language_id) VALUES (?, ?)");
    foreach ($languages as $lang) {
        $insertLang->execute([$submissionId, $lang['id']]);
    }
} catch (PDOException $e) {
    print('Ошибка БД: ' . $e->getMessage());
    exit();
}

header('Location: index.php?submit=1');
?>