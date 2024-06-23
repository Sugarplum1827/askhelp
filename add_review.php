<?php
include("app/db.conn.php");
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];  // Assuming user is logged in and their ID is stored in the session
    $username = $_SESSION['username']; // Assuming username is also stored in the session
    $rating = $_POST['rating'];
    $review_text = $_POST['review_text'];

    $stmt = $conn->prepare("INSERT INTO reviews (user_id, username, rating, review_text) VALUES (?, ?, ?, ?)");
    $stmt->bindParam(1, $user_id);
    $stmt->bindParam(2, $username);
    $stmt->bindParam(3, $rating);
    $stmt->bindParam(4, $review_text);
    
    if ($stmt->execute()) {
        header("Location: reviews.php");
    } else {
        echo "Error: " . $stmt->errorInfo()[2];
    }
}
?>
