<?php 

$sName = "localhost";
$uName = "testuser";
$pass = "3k9b0r4b";
$db_name = "chat_app_db";

try {
    $conn = new PDO("mysql:host=$sName;dbname=$db_name;charset=utf8mb4", $uName, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
