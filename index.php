<?php
session_start();

if (isset($_SESSION['username'])) {
    header("Location: home.php");
    exit;
}

include("app/db.conn.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT user_id, username, password, verified FROM users WHERE username = ?");
    $stmt->bindParam(1, $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        if ($user['verified'] == 0) {
            header("Location: index.php?error=Your%20account%20is%20not%20verified%20yet.%20Please%20contact%20the%20admin.");
            exit;
        } else {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            if ($username == 'admin') {
                header("Location: admin.php");
            } else {
                header("Location: home.php");
            }
            exit;
        }
    } else {
        header("Location: index.php?error=Invalid%20username%20or%20password.");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat App - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="img/logo.png">
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="w-400 p-5 shadow rounded">
        <form method="post" action="index.php">
            <div class="d-flex justify-content-center align-items-center flex-column">
                <img src="img/logo.png" class="w-25">
                <h3 class="display-4 fs-1 text-center">LOGIN</h3>
            </div>
            <?php if (isset($_GET['error'])) { ?>
            <div class="alert alert-warning" role="alert">
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
            <?php } ?>
            <div class="mb-3">
                <label class="form-label">User name</label>
                <input type="text" class="form-control" name="username" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">LOGIN</button>
            <a href="signup.php">Sign Up</a>
        </form>
    </div>
</body>
</html>
