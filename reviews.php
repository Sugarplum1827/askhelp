<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    die();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Star Reviews</title>
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
        .star-rating input:hover ~ label {
            color: #f5b301;
        }
    </style>
</head>
<body>
<div class="w-400 shadow p-4 rounded">
    	<a href="home.php"
    	   class="fs-4 link-dark">&#8592;</a>
    <h1>Star Reviews</h1>
    <form action="add_review.php" method="post">
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
        <br>
        <label for="review_text">Review:</label>
        <textarea id="review_text" name="review_text" rows="4" required></textarea>
        <br>
        <button type="submit">Submit Review</button>
    </form>
    <hr>
    <h2>All Reviews</h2>
    <div id="reviews">
        <?php include("get_reviews.php"); ?>
    </div>
</body>
</html>
