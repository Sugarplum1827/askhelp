<?php
// Include the database connection file
include("app/db.conn.php");

// Define the admin details
$username = 'admin';
$password = 'admin123';
$name = 'Admin';
$valid_id = 'admin_id';
$verified = 1;

// Hash the password
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

try {
    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO users (username, password, name, valid_id, verified) VALUES (?, ?, ?, ?, ?)");

    // Bind the parameters
    $stmt->bindParam(1, $username);
    $stmt->bindParam(2, $hashed_password);
    $stmt->bindParam(3, $name);
    $stmt->bindParam(4, $valid_id);
    $stmt->bindParam(5, $verified);

    // Execute the statement
    $stmt->execute();

    echo "Admin user inserted successfully.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
