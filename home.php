<?php 
session_start();

if (isset($_SESSION['username'])) {
    // Database connection file
    include 'app/db.conn.php';
    include 'app/helpers/user.php';
    include 'app/helpers/conversations.php';
    include 'app/helpers/timeAgo.php';
    include 'app/helpers/last_chat.php';

    // Getting User data
    $user = getUser($_SESSION['username'], $conn);

    // Getting User conversations
    $conversations = getConversation($user['user_id'], $conn);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat App - Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="img/logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .online {
            width: 10px;
            height: 10px;
            background-color: green;
            border-radius: 50%;
        }
        .chat-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
        }
        .chat-item img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }
        .chat-item h3 {
            margin: 0 10px;
        }
        .chat-item small {
            display: block;
        }
    </style>
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="p-2 w-400 rounded shadow">
        <div>
            <div class="d-flex mb-3 p-3 bg-light justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <img src="uploads/<?=$user['p_p']?>" class="w-25 rounded-circle">
                    <h3 class="fs-xs m-2"><?=$user['name']?></h3> 
                </div>
                <a href="logout.php" class="btn btn-dark">Logout</a>
                <a href="call_logs.php" class="btn btn-secondary">Logs</a>
            </div>

            <div class="input-group mb-3">
                <input type="text" placeholder="Search..." id="searchText" class="form-control">
                <button class="btn btn-primary" id="searchBtn">
                    <i class="fa fa-search"></i>
                </button>       
            </div>
            <ul id="chatList" class="list-group mvh-50 overflow-auto">
                <?php if (!empty($conversations)) { ?>
                    <?php foreach ($conversations as $conversation) { ?>
                        <li class="list-group-item">
                            <a href="chat.php?user=<?=$conversation['username']?>" class="chat-item">
                                <div class="d-flex align-items-center">
                                    <img src="uploads/<?=$conversation['p_p']?>" class="rounded-circle">
                                    <div>
                                        <h3 class="fs-xs m-2"><?=$conversation['name']?></h3>
                                        <small><?=lastChat($_SESSION['user_id'], $conversation['user_id'], $conn)?></small>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <?php if (last_seen($conversation['last_seen']) == "Active") { ?>
                                        <div title="online">
                                            <div class="online"></div>
                                        </div>
                                    <?php } ?>
									<div class="d-flex align-items-center">
                                    <a href="call.php?user=<?=$conversation['username']?>" class="ml-2" title="Call">
                                        <i class="fa fa-phone fa-lg text-primary"></i>
                                    </a>
                                </div>
                            </a>
                        </li>
                    <?php } ?>
                <?php } else { ?>
                    <div class="alert alert-info text-center">
                        <i class="fa fa-comments d-block fs-big"></i>
                        No messages yet, Start the conversation
                    </div>
                <?php } ?>
            </ul>
        </div>
    </div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function() {
        // Search
        $("#searchText").on("input", function() {
            var searchText = $(this).val();
            if (searchText == "") return;
            $.post('app/ajax/search.php', { key: searchText }, function(data, status) {
                $("#chatList").html(data);
            });
        });

        // Search using the button
        $("#searchBtn").on("click", function() {
            var searchText = $("#searchText").val();
            if (searchText == "") return;
            $.post('app/ajax/search.php', { key: searchText }, function(data, status) {
                $("#chatList").html(data);
            });
        });

        // Auto update last seen for logged in user
        let lastSeenUpdate = function() {
            $.get("app/ajax/update_last_seen.php");
        }
        lastSeenUpdate();

        // Auto update last seen every 10 sec
        setInterval(lastSeenUpdate, 10000);
    });
</script>
</body>
</html>
<?php
} else {
    header("Location: index.php");
    exit;
}
?>
