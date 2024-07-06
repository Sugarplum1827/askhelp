<!DOCTYPE html>
<html lang="en">
<head>
</head>
<body>
    <div class="w-400 shadow p-4 rounded mx-auto mt-5">
        <a href="log.php" class="fs-4 link-dark">&#8592;</a>

</body>
</html>


<?php
session_start();

if (isset($_SESSION['username'])) {
    if (isset($_GET['user'])) {
        include 'app/db.conn.php';
        include 'app/helpers/user.php';

        $userToCall = getUser($_GET['user'], $conn);

        if (!empty($userToCall)) {
            $callerId = $_SESSION['user_id'];
            $receiverId = $userToCall['user_id'];
            $stmt = $conn->prepare("INSERT INTO call_logs (caller_id, receiver_id) VALUES (?, ?)");
            $stmt->execute([$callerId, $receiverId]);

            echo "<h1>Calling " . htmlspecialchars($userToCall['name']) . "...</h1>";
        } else {
            echo "<h1>User not found</h1>";
        }
    } else {
        echo "<h1>No user specified for the call</h1>";
    }
} else {
    header("Location: index.php");
    exit;
}
?>
