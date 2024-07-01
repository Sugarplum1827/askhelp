<?php

session_start();

# Check if the user is logged in
if (isset($_SESSION['username'])) {
    # Check if the key is submitted
    if (isset($_POST['key'])) {
        # Database connection file
        include '../db.conn.php';

        # Creating a simple search algorithm
        $key = "%{$_POST['key']}%";

        $sql = "SELECT * FROM users
                WHERE (username LIKE ? OR name LIKE ?)
                AND user_id != ?
                AND is_agency = 1";  # Only show government agency users
        $stmt = $conn->prepare($sql);
        $stmt->execute([$key, $key, $_SESSION['user_id']]);

        if ($stmt->rowCount() > 0) { 
            $users = $stmt->fetchAll();

            foreach ($users as $user) { ?>
<li class="list-group-item">
    <a href="chat.php?user=<?= htmlspecialchars($user['username']) ?>"
       class="d-flex align-items-center p-2">
        <div class="d-flex align-items-center">
            <img src="uploads/<?= htmlspecialchars($user['p_p']) ?>"
                 class="w-10 rounded-circle">
            <h3 class="fs-xs m-2"><?= htmlspecialchars($user['name']) ?></h3>
            <div class="ml-2 d-flex align-items-center">
                <a href="call.php?user=<?= htmlspecialchars($user['username']) ?>" title="Call" class="mr-2">
                    <i class="fa fa-phone fa-lg text-primary"></i>
                </a>
                <a href="all_review.php?user_id=<?= htmlspecialchars($user['user_id']) ?>" title="Review">
                    <i class="fa fa-star fa-lg text-warning"></i>
                </a>
            </div>
        </div>
    </a>
</li>
<?php 
            } 
        } else { 
?>
<div class="alert alert-info text-center">
    <i class="fa fa-user-times d-block fs-big"></i>
    The user "<?= htmlspecialchars($_POST['key']) ?>" is not found.
</div>
<?php 
        }
    }
} else {
    header("Location: ../../index.php");
    exit;
}
?>
