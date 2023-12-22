<?php
// update_checklist.php

// Assuming you have a database connection already established

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toDoId'])) {
    require_once 'db.php';
    $toDoId = $_POST['toDoId'];

    // Perform the update in the database
    $stmt = $conn->prepare("UPDATE toDoList SET checkList = NOT checkList WHERE id = ?");
    $stmt->bind_param("i", $toDoId);
    $stmt->execute();

    // You might want to return some response
    if ($stmt->affected_rows > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Checklist updated successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update checklist.']);
    }
} else {
    // Handle invalid request
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}
?>
