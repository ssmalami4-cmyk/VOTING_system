<?php
$password = "123456"; 
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

echo $hashedPassword; 
?>