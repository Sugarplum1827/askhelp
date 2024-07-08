<?php
include("app/db.conn.php");

try {
    $stmt = $conn->prepare("SELECT u.name AS reviewed_name, r.username AS reviewer_username, r.rating, r.review_text, r.created_at 
                            FROM reviews r
                            JOIN users u ON r.reviewed_user_id = u.user_id 
                            ORDER BY u.name, r.created_at DESC");
    $stmt->execute();
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log($e->getMessage(), 3, 'error_log.txt');
    $reviews = [];
    $error_message = "An error occurred while fetching reviews. Please try again later.";
}

$grouped_reviews = [];
foreach ($reviews as $review) {
    $grouped_reviews[$review['reviewed_name']][] = $review;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Reviews</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .reviews-list {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
        .reviewed-user {
            margin-bottom: 30px;
        }
        .reviewed-user h2 {
            margin: 0 0 10px 0;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        .review {
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }
        .review:last-child {
            border-bottom: none;
        }
        .review h3 {
            margin: 0;
        }
        .review p {
            margin: 10px 0;
        }
        .review small {
            color: #666;
        }
        .star {
            color: #ffc107;
        }
        .star-empty {
            color: #ddd;
        }
    </style>
</head>
<body>
    <div class="reviews-list">
        <a href="home.php" class="fs-4 link-dark">&#8592; Back to Home</a>
        <h2>All Reviews</h2>
        <div class="reviews">
            <a href="reviews.php" title="Add Review">
                <i class="fa fa-star fa-lg text-warning"></i> Add Review
            </a>
        </div>

        <?php if (!empty($grouped_reviews)): ?>
            <?php foreach ($grouped_reviews as $reviewed_name => $user_reviews): ?>
                <div class="reviewed-user">
                    <h2>Reviews for <?= htmlspecialchars($reviewed_name, ENT_QUOTES, 'UTF-8') ?></h2>
                    <?php foreach ($user_reviews as $review): ?>
                        <div class="review">
                            <h3>Reviewed by <?= htmlspecialchars($review['reviewer_username'], ENT_QUOTES, 'UTF-8') ?></h3>
                            <p>
                                Rating: 
                                <?php for ($i = 0; $i < 5; $i++): ?>
                                    <span class="<?= $i < $review['rating'] ? 'star' : 'star-empty' ?>">&#9733;</span>
                                <?php endfor; ?>
                            </p>
                            <p><?= htmlspecialchars($review['review_text'], ENT_QUOTES, 'UTF-8') ?></p>
                            <small>Posted on: <?= htmlspecialchars($review['created_at'], ENT_QUOTES, 'UTF-8') ?></small>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p><?= isset($error_message) ? htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8') : "No reviews available." ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
