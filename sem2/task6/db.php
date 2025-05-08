<?php
// database.php
$db = new PDO('mysql:host=localhost;dbname=u68788', 'u68788', '9724771', [
    PDO::ATTR_PERSISTENT => true,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);