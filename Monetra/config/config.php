<?php 
$db_host = 'localhost';
$db_username = 'root';
$db_name = 'monetra';
$db_password = '';

try {
     $pdo = new PDO("mysql:localhost=$db_host;dbname=$db_name", $db_username, $db_password);
     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
     die("Database Connection Probleme : " . $e->getMessage());
}

?>