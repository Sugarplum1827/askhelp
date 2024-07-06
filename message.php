<?php
// Connect to the database
$conn = new mysqli("localhost", "testuser", "3k9b0r4b", "chat_app_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user message through AJAX
$getMesg = $conn->real_escape_string($_POST['text']);

// Prepare and execute the SQL query
$check_data = $conn->prepare("SELECT replies FROM chatbot WHERE queries LIKE CONCAT('%', ?, '%')");
$check_data->bind_param("s", $getMesg);
$check_data->execute();
$result = $check_data->get_result();

// If the user query matches a database query, show the reply; otherwise, go to the else statement
if ($result->num_rows > 0) {
    // Fetch the reply from the database according to the user query
    $fetch_data = $result->fetch_assoc();
    // Store the reply to a variable which we'll send to AJAX
    $reply = $fetch_data['replies'];
    echo $reply;
} else {
    echo "Sorry, I can't understand you!";
}

// Close the connection
$conn->close();
?>


