<?php
include("app/db.conn.php");

$agencies = [
    ['username' => 'agency1', 'password' => 'agency123', 'name' => 'Agency One', 'valid_id' => 'agency_id_1'],
    ['username' => 'agency2', 'password' => 'agency456', 'name' => 'Agency Two', 'valid_id' => 'agency_id_2'],

];

foreach ($agencies as $agency) {
    $hashed_password = password_hash($agency['password'], PASSWORD_BCRYPT);
    $stmt = $conn->prepare("INSERT INTO users (username, password, name, valid_id, is_admin, is_agency, verified) 
                            VALUES (?, ?, ?, ?, ?, ?, ?)");
    $is_admin = 0;
    $is_agency = 1;
    $verified = 1;

    if ($stmt->execute([$agency['username'], $hashed_password, $agency['name'], $agency['valid_id'], $is_admin, $is_agency, $verified])) {
        echo "Agency " . htmlspecialchars($agency['name']) . " added successfully.<br>";
    } else {
        echo "Failed to add agency " . htmlspecialchars($agency['name']) . ".<br>";
    }
}

$conn = null;
?>
