<?php
header('Content-Type: text/html; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  if (!empty($_GET['submit'])) {
    print('Спасибо, ваши данные успешно сохранены.');
  }
  include('contact_form.php');
  
  exit();
}


$fullName = isset($_POST['fullName']) ? $_POST['fullName'] : '';
$phoneNumber = isset($_POST['phoneNumber']) ? preg_replace('/\D/', '', $_POST['phoneNumber']) : '';
$userEmail = isset($_POST['userEmail']) ? $_POST['userEmail'] : '';
$eventDate = isset($_POST['eventDate']) ? $_POST['eventDate'] : '';
$gender = isset($_POST['gender']) ? $_POST['gender'] : '';
$selectedLanguages = isset($_POST['selectedLanguages']) ? $_POST['selectedLanguages'] : '';
$userBio = isset($_POST['userBio']) ? $_POST['userBio'] : '';
$agreement = isset($_POST['agreement']) ? $_POST['agreement'] : '';

$programmingLanguages = ($selectedLanguages != '') ? implode(", ", $selectedLanguages) : [];

$errorFlag = FALSE;

if (empty($_POST['fullName']) || preg_match('~[^а-яА-ЯёЁ ]~u', $fullName) || (strlen($fullName) > 255)) {
    echo "Пожалуйста, укажите корректное имя.\n";
    $errorFlag = TRUE;
}
if(strlen($phoneNumber) != 11){
    echo "Пожалуйста, укажите номер телефона.\n";
    $errorFlag = TRUE;
}
if ((filter_var($userEmail, FILTER_VALIDATE_EMAIL) === false) || empty($_POST['userEmail']) || (strlen($userEmail) > 255)) {
    echo "e-mail адрес '$userEmail' указан неверно или пуст.\n";
    $errorFlag = TRUE;
}
if (empty($_POST['eventDate'])) {
    echo "Пожалуйста, укажите дату.\n";
    $errorFlag = TRUE;
}
if (empty($_POST['gender'])) {
    echo "Пожалуйста, выберите пол.\n";
    $errorFlag = TRUE;
}
if (empty($_POST['selectedLanguages'])) {
    echo "Пожалуйста, выберите хотя бы один язык.\n";
    $errorFlag = TRUE;
}
if (strlen($userBio) > 65535) {
    echo "Длина биографии слишком большая\n";
    $errorFlag = TRUE;
}
if (empty($_POST['agreement'])) {
    echo "Пожалуйста, ознакомьтесь с условиями.\n";
    $errorFlag = TRUE;
}
if ($errorFlag) {
    
    exit();
}



$dbUser = 'u68788'; 
$dbPass = '9724771'; 
$database = new PDO('mysql:host=localhost;dbname=u68788', $dbUser, $dbPass,
  [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); 

$placeholders = implode(',', array_fill(0, count($selectedLanguages), '?'));

try {
    $languageQuery = $database->prepare("SELECT id, name FROM programming_languages WHERE name IN ($placeholders)");
    foreach ($selectedLanguages as $key => $value)
        $languageQuery->bindValue(($key + 1), $value);
    $languageQuery->execute();
    $programmingLanguages = $languageQuery->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    print('Error : ' . $e->getMessage());
    exit();
}

echo $languageQuery->rowCount() . '**' . count($selectedLanguages);


try {
    $insertData = $database->prepare("INSERT INTO user_submissions (fullName, phoneNumber, userEmail, eventDate, gender, userBio) VALUES (?, ?, ?, ?, ?, ?)");
    $insertData->execute([$fullName, $phoneNumber, $userEmail, $eventDate, $gender, $userBio]);
    $submissionId = $database->lastInsertId();
    $insertLanguages = $database->prepare("INSERT INTO submission_languages (submission_id, language_id) VALUES (?, ?)");
    foreach($programmingLanguages as $row)
        $insertLanguages->execute([$submissionId, $row['id']]);
} catch(PDOException $e) {
    print('Error : ' . $e->getMessage());
    exit();
}

header('Location: ?submit=1');