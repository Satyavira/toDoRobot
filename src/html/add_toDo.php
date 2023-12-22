<?php
// add_toDo.php

// Include necessary files and initialize any required variables
require_once 'db.php';

// Ensure that the necessary data is present in the POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['userId'], $_POST['content'], $_POST['label'])) {
    $content = $_POST['content'];
    $label = $_POST['label'];
    $userId = $_POST['userId'];

    // Your code to insert the new to-do item into the database
    $stmt = $conn->prepare("INSERT INTO toDoList (userId, content, label, checkList) VALUES (?, ?, ?, false)");
    $stmt->bind_param("sss", $userId, $content, $label);
    
    if ($stmt->execute()) {
        // Get the ID of the inserted row
        $toDoId = $conn->insert_id;

        // Return a response, e.g., success or failure
        if ($toDoId > 0) {
            echo json_encode(['success' => true, 'message' => 'To-do item added successfully', 'data' => ['id' => $toDoId]]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error adding to-do item']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Error executing SQL statement']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Missing data in the request']);
}
?>
