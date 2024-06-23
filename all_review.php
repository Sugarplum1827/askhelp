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
    <title>All Reviews</title>
    <!-- Include Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Add some basic styles -->
    <style>
        .w-400 {
            width: 400px;
        }
        .shadow {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .p-4 {
            padding: 1rem;
        }
        .rounded {
            border-radius: 8px;
        }
        .fs-4 {
            font-size: 1.5rem;
        }
        .link-dark {
            color: #000;
            text-decoration: none;
        }
        .ml-2 {
            margin-left: 0.5rem;
        }
        .d-flex {
            display: flex;
        }
        .align-items-center {
            align-items: center;
        }
        .text-warning {
            color: #ffc107;
        }
        .add-rating {
            display: flex;
            align-items: center;
        }
        .add-rating p {
            margin: 0;
            margin-right: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="w-400 shadow p-4 rounded">
        <a href="home.php" class="fs-4 link-dark">&#8592;</a>
       
        <h2>All Reviews</h2>
        <div id="reviews">
            <?php include("get_reviews.php"); ?>
        </div>
        <div class="add-rating ml-2 d-flex align-items-center">
            <p>Add Rating -</p>
            <a href="reviews.php" title="Review">
                <i class="fa fa-star fa-lg text-warning"></i>
            </a>
        </div>
    </div>
</body>
</html>
