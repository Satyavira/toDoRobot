<?php
// refresh_todolists.php

// ... (Include necessary files and initialize any required variables)
require_once 'db.php';

session_start();
// Your existing code to fetch updated to-do lists
$search = isset($_GET['search']) ? $_GET['search'] : '';
$userId = $_SESSION['id_user'];
$search = "%" . $search . "%";
// Fetch to-do lists
$stmt = $conn->prepare("SELECT * 
                        FROM toDoList 
                        WHERE checkList != true AND (content LIKE ? AND userId LIKE ?) 
                        ORDER BY createdAt DESC");
$stmt->bind_param("ss", $search, $userId);
$stmt->execute();
$result = $stmt->get_result();
$toDoLists = $result->fetch_all(MYSQLI_ASSOC);

$stmt = $conn->prepare("SELECT *
                        FROM toDoList
                        WHERE checkList != false AND (content LIKE ? AND userId LIKE ?)
                        ORDER BY createdAt DESC");
$stmt->bind_param("ss", $search, $userId);
$stmt->execute();
$result = $stmt->get_result();
$toDoListsChecked = $result->fetch_all(MYSQLI_ASSOC);

// Output the HTML content for the updated to-do lists
foreach ($toDoLists as $toDoList) {
    // Output the HTML structure for each to-do list item
    echo '<div class="toDo" id="toDo_' . $toDoList['id'] . '">';
    
    // Checklist area
    echo '<div class="checklist-area" onclick="toggleCheckList(' . $toDoList['id'] . ')">';
    echo '<svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" id="checkList" style="background: none;">';
    echo '<circle cx="20" cy="20" r="19.5" fill="white" stroke="#C5C4C4" />';
    echo '</svg>';
    echo '</div>';
    
    // Text area
    echo '<div class="text" onclick="editToDo(' . $toDoList['id'] . ')">';
    echo '<div class="toDo-label">';
    echo $toDoList['label'];
    echo '</div>';
    echo '<div class="toDo-text">';
    echo $toDoList['content'];
    echo '</div>';
    echo '</div>';
    include('form_actions.php');
    echo '</div>';
}

foreach ($toDoListsChecked as $toDoList) {
    // Output the HTML structure for each to-do list item
    echo '<div class="toDo checked" id="toDo_' . $toDoList['id'] . '">';
    
    // Checklist area
    echo '<div class="checklist-area" onclick="toggleCheckList(' . $toDoList['id'] . ')">';
    echo '<svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" id="checkList" style="background: none;">';
    echo '<circle cx="20" cy="20" r="19.5" fill="black" stroke="#C5C4C4" />';
    echo '</svg>';
    echo '</div>';
    
    // Text area
    echo '<div class="text" onclick="editToDo(' . $toDoList['id'] . ')">';
    echo '<div class="toDo-label">';
    echo $toDoList['label'];
    echo '</div>';
    echo '<div class="toDo-text">';
    echo $toDoList['content'];
    echo '</div>';
    echo '</div>';
    include('form_actions.php');
    echo '</div>';
}
?>
