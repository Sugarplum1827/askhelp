<?php
session_start();

if (isset($_SESSION['username'])) {
    # database connection file
    include 'app/db.conn.php';

    # Fetching call logs made by the current user
    $stmt = $conn->prepare("SELECT 
                                c.id,
                                u1.username AS caller,
                                u2.username AS receiver,
                                c.call_time 
                            FROM call_logs c
                            JOIN users u1 ON c.caller_id = u1.user_id
                            JOIN users u2 ON c.receiver_id = u2.user_id
                            WHERE c.caller_id = ?
                            ORDER BY c.call_time DESC");
    $stmt->execute([$_SESSION['user_id']]);
    $callLogs = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Call Logs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="img/logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="w-400 shadow p-4 rounded mx-auto mt-5">
        <a href="home.php" class="fs-4 link-dark">&#8592;</a>
        <h2>Call Logs</h2>
        <ul class="list-group mvh-50 overflow-auto">
            <?php if (!empty($callLogs)) { ?>
                <?php foreach ($callLogs as $log) { ?>
                    <li class="list-group-item">
                        <strong>Caller:</strong> <?= htmlspecialchars($log['caller']) ?><br>
                        <strong>Receiver:</strong> <?= htmlspecialchars($log['receiver']) ?><br>
                        <strong>Time:</strong> <?= htmlspecialchars($log['call_time']) ?>
                    </li>
                <?php } ?>
            <?php } else { ?>
                <div class="alert alert-info text-center">
                    <i class="fa fa-phone d-block fs-big"></i>
                    No call logs available.
                </div>
            <?php } ?>
        </ul>
    </div>
</body>
</html>
<?php
} else {
    header("Location: index.php");
    exit;
}
?>
