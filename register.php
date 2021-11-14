<?php
require 'database.php';

$name = 'ZTC';
$email = 'ztcodin@gmail.com';
$hashed_password= password_hash("123", PASSWORD_DEFAULT);

$pdo = $pdo->prepare("INSERT INTO users(name, email, password) VALUES (?, ?, ?)");
$pdo->execute([$name, $email, $hashed_password]);


?>