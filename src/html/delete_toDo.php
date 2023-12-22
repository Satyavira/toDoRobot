<?php
// delete_post.php

// ... (Include necessary files and initialize any required variables)

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure that the necessary data is present in the POST request
    if (isset($_POST['toDoId'])) {
        require_once 'db.php';
        $toDoId = $_POST['toDoId'];

        // Your code to delete the to-do item from the database
        $stmt = $conn->prepare("DELETE FROM toDoList WHERE id = ?");
        $stmt->bind_param("i", $toDoId); // i - integer
        $stmt->execute();

        // Return a response, e.g., success or failure
        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'To-do item deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error deleting to-do item']);
        }

        // Close the statement
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Missing data in the request']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
