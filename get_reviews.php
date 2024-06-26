<?php
include("app/db.conn.php");

try {
    $stmt = $conn->prepare("SELECT users.username, reviews.rating, reviews.review_text, reviews.created_at 
                            FROM reviews 
                            JOIN users ON reviews.reviewed_user_id = users.user_id 
                            ORDER BY reviews.created_at DESC");
    $stmt->execute();
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($reviews) {
        foreach ($reviews as $review) {
            echo "<div class='review'>";
            echo "<h3>" . htmlspecialchars($review['username'], ENT_QUOTES, 'UTF-8') . "</h3>";
            echo "<p>Rating: ";
            for ($i = 0; $i < 5; $i++) {
                echo $i < $review['rating'] ? "&#9733;" : "&#9734;";
            }
            echo "</p>";
            echo "<p>" . htmlspecialchars($review['review_text'], ENT_QUOTES, 'UTF-8') . "</p>";
            echo "<small>Posted on: " . htmlspecialchars($review['created_at'], ENT_QUOTES, 'UTF-8') . "</small>";
            echo "</div><hr>";
        }
    } else {
        echo "<p>No reviews available.</p>";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
