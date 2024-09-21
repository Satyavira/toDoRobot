<?php

// refresh_labels.php
session_start();
require_once 'db.php';
require_once 'function.php';
$queryLabels = "SELECT DISTINCT label
                    FROM toDoList
                    WHERE userId LIKE ?";
  $stmtLabels = $conn->prepare($queryLabels);
  $stmtLabels->bind_param("s", $_SESSION["id_user"]);
  $stmtLabels->execute();
  $labels = $stmtLabels->get_result()->fetch_all(MYSQLI_ASSOC);

foreach ($labels as $label) {
    echo '<li class="nav-item"><a class="nav-link label-link" href="#">' . $label['label'] . '</a></li>';
}

?>