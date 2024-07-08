<?php 
session_start();

if (isset($_SESSION['username'])) {
    include 'app/db.conn.php';
    include 'app/helpers/user.php';
    include 'app/helpers/conversations.php';
    include 'app/helpers/timeAgo.php';
    include 'app/helpers/last_chat.php';

    $user = getUser($_SESSION['username'], $conn);
    $conversations = getConversation($user['user_id'], $conn);
    $adminUser = getAdminUser($conn);
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
        .wrapper {
            width: 370px;
            background: #fff;
            border-radius: 5px;
            border: 1px solid lightgrey;
            border-top: 0px;
            position: fixed;
            bottom: 80px;
            right: 20px;
            display: none;
            z-index: 1000;
        }
        .wrapper .title {
            background: #007bff;
            color: #fff;
            font-size: 20px;
            font-weight: 500;
            line-height: 60px;
            text-align: center;
            border-bottom: 1px solid #006fe6;
            border-radius: 5px 5px 0 0;
        }
        .wrapper .form {
            padding: 20px 15px;
            min-height: 400px;
            max-height: 400px;
            overflow-y: auto;
        }
        .wrapper .form .inbox {
            width: 100%;
            display: flex;
            align-items: baseline;
        }
        .wrapper .form .user-inbox {
            justify-content: flex-end;
            margin: 13px 0;
        }
        .wrapper .form .inbox .icon {
            height: 40px;
            width: 40px;
            color: #fff;
            text-align: center;
            line-height: 40px;
            border-radius: 50%;
            font-size: 18px;
            background: #007bff;
        }
        .wrapper .form .inbox .msg-header {
            max-width: 53%;
            margin-left: 10px;
        }
        .form .inbox .msg-header p {
            color: #fff;
            background: #007bff;
            border-radius: 10px;
            padding: 8px 10px;
            font-size: 14px;
            word-break: break-all;
        }
        .form .user-inbox .msg-header p {
            color: #333;
            background: #efefef;
        }
        .wrapper .typing-field {
            display: flex;
            height: 60px;
            width: 100%;
            align-items: center;
            justify-content: space-evenly;
            background: #efefef;
            border-top: 1px solid #d9d9d9;
            border-radius: 0 0 5px 5px;
        }
        .wrapper .typing-field .input-data {
            height: 40px;
            width: 335px;
            position: relative;
        }
        .wrapper .typing-field .input-data input {
            height: 100%;
            width: 100%;
            outline: none;
            border: 1px solid transparent;
            padding: 0 80px 0 15px;
            border-radius: 3px;
            font-size: 15px;
            background: #fff;
            transition: all 0.3s ease;
        }
        .typing-field .input-data input:focus {
            border-color: rgba(0,123,255,0.8);
        }
        .input-data input::placeholder {
            color: #999999;
            transition: all 0.3s ease;
        }
        .input-data input:focus::placeholder {
            color: #bfbfbf;
        }
        .wrapper .typing-field .input-data button {
            position: absolute;
            right: 5px;
            top: 50%;
            height: 30px;
            width: 65px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            outline: none;
            opacity: 0;
            pointer-events: none;
            border-radius: 3px;
            background: #007bff;
            border: 1px solid #007bff;
            transform: translateY(-50%);
            transition: all 0.3s ease;
        }
        .wrapper .typing-field .input-data input:valid ~ button {
            opacity: 1;
            pointer-events: auto;
        }
        .typing-field .input-data button:hover {
            background: #006fef;
        }
        .chatbot-icon {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            font-size: 24px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1001;
            transition: background 0.3s ease;
        }
        .chatbot-icon:hover {
            background: #0056b3;
        }
        .admin-chat-btn {
            position: fixed;
            bottom: 20px;
            left: 20px;
            z-index: 1001;
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

    <div class="admin-chat-btn">
        <a href="chat.php?user=<?=$adminUser['username']?>" class="btn btn-primary">Admin</a>
    </div>

    <button class="chatbot-icon">
        <i class="fa fa-envelope fa-lg text-dark"></i>
    </button>
    <div class="wrapper">
        <div class="title">Chatbot</div>
        <div class="form">
            <div class="bot-inbox inbox">
                <div class="icon">
                    <i class="fa fa-user"></i>
                </div>
                <div class="msg-header">
                    <p>Hello there, how can I help you?</p>
                </div>
            </div>
        </div>
        <div class="typing-field">
            <div class="input-data">
                <input id="data" type="text" placeholder="Type something here.." required>
                <button id="send-btn">Send</button>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#searchText").on("input", function() {
                var searchText = $(this).val();
                if (searchText == "") return;
                $.post('app/ajax/search.php', { key: searchText }, function(data, status) {
                    $("#chatList").html(data);
                });
            });

            $("#searchBtn").on("click", function() {
                var searchText = $("#searchText").val();
                if (searchText == "") return;
                $.post('app/ajax/search.php', { key: searchText }, function(data, status) {
                    $("#chatList").html(data);
                });
            });

            let lastSeenUpdate = function() {
                $.get("app/ajax/update_last_seen.php");
            }
            lastSeenUpdate();
            setInterval(lastSeenUpdate, 10000);

            function sendMessage(message) {
                var $msg = '<div class="user-inbox inbox"><div class="msg-header"><p>'+ message +'</p></div></div>';
                $(".form").append($msg);
                $("#data").val('');
                $.ajax({
                    url: 'message.php',
                    type: 'POST',
                    data: 'text='+message,
                    success: function(result){
                        var $replay = '<div class="bot-inbox inbox"><div class="icon"><i class="fa fa-user"></i></div><div class="msg-header"><p>'+ result +'</p></div></div>';
                        $(".form").append($replay);
                        $(".form").scrollTop($(".form")[0].scrollHeight);
                    }
                });
            }

            $("#send-btn").on("click", function(){
                var value = $("#data").val();
                if (value) {
                    sendMessage(value);
                }
            });

            $(".chatbot-icon").on("click", function(){
                $(".wrapper").toggle();
            });
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
