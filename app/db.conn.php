<?php 

# Server name
$sName = "localhost";
# User name
$uName = "testuser";
# Password
$pass = "3k9b0r4b";
# Database name
$db_name = "chat_app_db";

# Creating database connection
try {
    # Create a new PDO instance
    $conn = new PDO("mysql:host=$sName;dbname=$db_name;charset=utf8mb4", $uName, $pass);
    # Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    # Handle connection error
    echo "Connection failed: " . $e->getMessage();
}
?>
