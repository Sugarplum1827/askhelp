<?php
include("app/db.conn.php");

$stmt = $conn->prepare("SELECT username, rating, review_text, created_at FROM reviews ORDER BY created_at DESC");
$stmt->execute();
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($reviews as $review) {
    echo "<div class='review'>";
    echo "<h3>" . htmlspecialchars($review['username']) . "</h3>";
    echo "<p>Rating: ";
    for ($i = 0; $i < 5; $i++) {
        if ($i < $review['rating']) {
            echo "&#9733;"; // Filled star
        } else {
            echo "&#9734;"; // Empty star
        }
    }
    echo "</p>";
    echo "<p>" . htmlspecialchars($review['review_text']) . "</p>";
    echo "<small>Posted on: " . htmlspecialchars($review['created_at']) . "</small>";
    echo "</div><hr>";
}
?>
