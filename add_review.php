<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    die();
}

include("app/db.conn.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $reviewed_user_id = intval($_POST['reviewed_user_id']);
    $rating = intval($_POST['rating']);
    $review_text = trim($_POST['review_text']);
    $created_at = date('Y-m-d H:i:s');

    // Fetch the username of the reviewer from the database
    $stmt = $conn->prepare("SELECT name FROM users WHERE user_id = ?");
    $stmt->bindParam(1, $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $username = $user['name'];

        if ($rating > 0 && $rating <= 5 && !empty($review_text)) {
            $stmt = $conn->prepare("INSERT INTO reviews (user_id, reviewed_user_id, username, rating, review_text, created_at) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bindParam(1, $user_id);
            $stmt->bindParam(2, $reviewed_user_id);
            $stmt->bindParam(3, $username);
            $stmt->bindParam(4, $rating);
            $stmt->bindParam(5, $review_text);
            $stmt->bindParam(6, $created_at);

            if ($stmt->execute()) {
                header("Location: all_review.php");
                exit();
            } else {
                echo "Error adding review.";
            }
        } else {
            echo "Invalid data. Please fill the form correctly.";
        }
    } else {
        echo "User not found.";
    }
} else {
    echo "Invalid request.";
}
?>
