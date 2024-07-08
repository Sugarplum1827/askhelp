<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['username'] != 'admin') {
    header("Location: index.php");
    die();
}

include("app/db.conn.php");

// Fetch unverified users
$stmt = $conn->prepare("SELECT user_id, username, name, valid_id, p_p FROM users WHERE verified = 0 AND reject = 0");
$stmt->execute();
$unverified_users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Verify or reject user
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['user_id'])) {
    $user_id = intval($_POST['user_id']);
    if (isset($_POST['verify'])) {
        $stmt = $conn->prepare("UPDATE users SET verified = 1 WHERE user_id = ?");
    } elseif (isset($_POST['reject'])) {
        $stmt = $conn->prepare("UPDATE users SET reject = 1 WHERE user_id = ?");
    }
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
    <style>
        .modal-content img {
            width: 100%;
            height: auto;
        }
        .admin-chat-btn {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1001;
        }
    </style>
    <script>
        function showImage(src) {
            document.getElementById('modalImage').src = src;
            var myModal = new bootstrap.Modal(document.getElementById('imageModal'));
            myModal.show();
        }
    </script>
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
                        Phone number: <?= htmlspecialchars($user['username']) ?> (Name: <?= htmlspecialchars($user['name']) ?>, ID: 
                        <a href="#" onclick="showImage('uploads/valid_ids/<?= htmlspecialchars($user['valid_id']) ?>')">
                            <?= htmlspecialchars($user['valid_id']) ?>
                        </a>,
                        <a href="#" onclick="showImage('uploads/<?= htmlspecialchars($user['p_p']) ?>')">
                            <?= htmlspecialchars($user['p_p']) ?>
                        </a>)
                        <form action="admin.php" method="post" class="d-inline">
                            <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                            <button type="submit" name="verify" class="btn btn-success">Verify</button>
                            <button type="submit" name="reject" class="btn btn-danger">Reject</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <a href="logout.php" class="btn btn-secondary mt-3">Logout</a>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">ID Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" class="img-fluid" alt="ID Image">
                </div>
            </div>
        </div>
    </div>

    <div class="admin-chat-btn">
        <a href="chat.php" class="btn btn-primary">Chat with Users</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js"></script>
</body>
</html>
