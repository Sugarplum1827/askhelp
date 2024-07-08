<?php

function getAllUsersExcept($user_id, $conn) {
    $sql = "SELECT * FROM users WHERE user_id != ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$user_id]);
    return $stmt->fetchAll();
}

function getUser($username, $conn) {
    $sql = "SELECT * FROM users WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$username]);
    
    if ($stmt->rowCount() === 1) {
        return $stmt->fetch();
    } else {
        return null;
    }
}

function getAdminUser($conn) {
    $sql = "SELECT * FROM users WHERE is_admin = 1 LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
