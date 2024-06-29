<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['username'] != 'admin') {
    header("Location: index.php");
    die();
}

include("app/db.conn.php");

// Fetch unverified users
$stmt = $conn->prepare("SELECT user_id, username, name, valid_id FROM users WHERE verified = 0");
$stmt->execute();
$unverified_users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Verify user
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['user_id'])) {
    $user_id = intval($_POST['user_id']);
    $stmt = $conn->prepare("UPDATE users SET verified = 1 WHERE user_id = ?");
    $stmt->bindParam(1, $user_id);
    $stmt->execute();
    header("Location: admin.php");
    die();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Admin Page</h1>
        <h2>Unverified Users</h2>
        <?php if (empty($unverified_users)): ?>
            <p>No unverified users found.</p>
        <?php else: ?>
            <ul class="list-group">
                <?php foreach ($unverified_users as $user): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                       Phone number: <?= htmlspecialchars($user['username']) ?> (Name: <?= htmlspecialchars($user['name']) ?>, ID: <?= htmlspecialchars($user['valid_id']) ?>)
                        <form action="admin.php" method="post" class="d-inline">
                            <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                            <button type="submit" class="btn btn-success">Verify</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <a href="logout.php" class="btn btn-secondary mt-3">Logout</a>
    </div>
</body>
</html>
