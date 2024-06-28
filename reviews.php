<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    die();
}

include("app/db.conn.php");

$stmt = $conn->prepare("SELECT user_id, name FROM users");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Star Reviews</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        .review {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
        }
        .star-rating {
            display: flex;
            direction: row-reverse;
            justify-content: flex-start;
        }
        .star-rating input {
            display: none;
        }
        .star-rating label {
            font-size: 2rem;
            color: #ccc;
            cursor: pointer;
        }
        .star-rating input:checked ~ label {
            color: #f5b301;
        }
        .star-rating input:hover ~ label,
        .star-rating label:hover,
        .star-rating label:hover ~ label {
            color: #f5b301;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="w-400 shadow p-4 rounded mx-auto mt-5">
        <a href="all_review.php" class="fs-4 link-dark">&#8592;</a>
        <h1>Star Reviews</h1>
        <form action="add_review.php" method="post" id="reviewForm">
            <div class="form-group">
                <label for="reviewed_user_id">Reviewing:</label>
                <select class="form-control" id="reviewed_user_id" name="reviewed_user_id" required>
                    <?php foreach ($users as $user): ?>
                        <option value="<?= $user['user_id']; ?>"><?= htmlspecialchars($user['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="rating">Rating:</label>
                <div class="star-rating">
                    <input type="radio" id="5-stars" name="rating" value="5" required>
                    <label for="5-stars">&#9733;</label>
                    <input type="radio" id="4-stars" name="rating" value="4" required>
                    <label for="4-stars">&#9733;</label>
                    <input type="radio" id="3-stars" name="rating" value="3" required>
                    <label for="3-stars">&#9733;</label>
                    <input type="radio" id="2-stars" name="rating" value="2" required>
                    <label for="2-stars">&#9733;</label>
                    <input type="radio" id="1-stars" name="rating" value="1" required>
                    <label for="1-stars">&#9733;</label>
                </div>
            </div>
            <div class="form-group">
                <label for="review_text">Review:</label>
                <textarea class="form-control" id="review_text" name="review_text" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit Review</button>
        </form>
        <hr>
    </div>
</div>
<script>
    document.getElementById('reviewForm').addEventListener('submit', function(e) {
        var rating = document.querySelector('input[name="rating"]:checked');
        var reviewText = document.getElementById('review_text').value;

        if (!rating) {
            e.preventDefault();
            alert('Please select a rating.');
            return false;
        }
        if (!reviewText) {
            e.preventDefault();
            alert('Please enter your review.');
            return false;
        }
    });
</script>
</body>
</html>
