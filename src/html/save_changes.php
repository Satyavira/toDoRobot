<?php
// save_changes.php

// ... (Include necessary files and initialize any required variables)

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure that the necessary data is present in the POST request
    if (isset($_POST['toDoId'], $_POST['editedContent'], $_POST['editedLabel'])) {
        require_once 'db.php';
        $toDoId = $_POST['toDoId'];
        $editedContent = $_POST['editedContent'];
        $editedLabel = $_POST['editedLabel'];

        // Your code to update the database with the edited content and label
        $stmt = $conn->prepare("UPDATE toDoList SET content = ?, label = ? WHERE id = ?");
        $stmt->bind_param("ssi", $editedContent, $editedLabel, $toDoId);
        $stmt->execute();

        // Return a response, e.g., success or failure
        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Changes saved successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error saving changes']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Missing data in the request']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
