<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>ToDoRobot</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
  <link rel="stylesheet" href="../css/style.css">
  <link rel="shortcut icon" href="../img/logo.png" type="image/x-icon">
</head>

<body>
  <script src="../js/index.js"></script>
  <?php
  // (A) NOT LOGGED IN
  session_start();
  require_once 'db.php';
  require_once 'function.php';
  $userId = $_SESSION['id_user'];
  $username = $_SESSION['username'];

  // (C) GET USER PROFILE
  $user = array('id' => "$userId", 'name' => "$username");

  // Ambil semua todolist dari database yang sesuai dengan user
  $search = isset($_GET['search']) ? $_GET['search'] : '';
  $search = "%" . $search . "%";

  // Query for unchecked to-do items
  $queryUnchecked = "SELECT *
                    FROM toDoList
                    WHERE checkList != true AND (content LIKE ? AND userId LIKE ?)
                    ORDER BY createdAt DESC";
  $stmtUnchecked = $conn->prepare($queryUnchecked);
  $stmtUnchecked->bind_param("ss", $search, $userId);
  $stmtUnchecked->execute();
  $toDoLists = $stmtUnchecked->get_result()->fetch_all(MYSQLI_ASSOC);

  // Query for checked to-do items
  $queryChecked = "SELECT *
                    FROM toDoList
                    WHERE checkList != false AND (content LIKE ? AND userId LIKE ?)
                    ORDER BY createdAt DESC";
  $stmtChecked = $conn->prepare($queryChecked);
  $stmtChecked->bind_param("ss", $search, $userId);
  $stmtChecked->execute();
  $toDoListsChecked = $stmtChecked->get_result()->fetch_all(MYSQLI_ASSOC);

  // Query for distinct labels
  $labels = getLabel();
  ?>

  <div id="container">
    <div id="sidebar">
      <!-- Sidebar content goes here -->
      <div id="user-info-container">
        <span id="username"><?= $user['name'] ?></span>
      </div>
      <ul class="nav flex-column"><a class="nav-link list-link" href="#" id="tagLabel">Tags</a>
        <ul class="nav" id="inside-sidebar-list">
          <?php if (count($labels) > 0 && $labels !== null) : ?>
            <?php foreach ($labels as $label) : ?>
              <li class="nav-item"><a class="nav-link label-link" href="#"><?= $label['label'] ?></a></li>
            <?php endforeach; ?>
          <?php else : ?>
            <!-- Display a message or take appropriate action if there are no to-do items. -->
          <?php endif; ?>
        </ul>
      </ul>
      <div class="signout">
        <a href="./logout.php" class="nav-link bottom-link" id="signout">Sign out</a>
      </div>
    </div>
    <div id="main-content" class="main-content">
      <!-- Main content goes here -->
      <div id="toDo-3">
        <div id="toDo-welcome">
          Good Day, <?= $user['name'] ?>
        </div>
        <div id="toDo-quote">
          Run your day or your day will run you
        </div>
        <div id="toDo-date">
          <span id="day">
            Saturday,
          </span>
          <span id="number">
            16
          </span>
          <span id="month">
            December
          </span>
        </div>
      </div>
      <div id="addToDo">
        <label for="addToDoInput" id="addToDo-label" onclick="addToDo(<?= $user['id'] ?>)">+</label>
        <input type="text" placeholder="Add ToDo" id="addToDoInput">
      </div>
      <div id="allToDo">
        <?php if (count($toDoLists) > 0 || count($toDoListsChecked) > 0) : ?>
          <?php foreach ($toDoLists as $toDoList) : ?>
            <div class="toDo" id="toDo_<?= $toDoList['id'] ?>">
              <!-- Clickable area for the checklist -->
              <div class="checklist-area" onclick="toggleCheckList(<?= $toDoList['id'] ?>)">
                <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" id="checkList" style="background: none;">
                  <circle cx="20" cy="20" r="19.5" fill="white" stroke="#C5C4C4" />
                </svg>
              </div>

              <!-- Clickable area for the rest of the content -->
              <div class="text" onclick="editToDo(<?= $toDoList['id'] ?>)">
                <div class="toDo-label">
                  <?= $toDoList['label'] ?>
                </div>
                <div class="toDo-text">
                  <?= $toDoList['content'] ?>
                </div>
              </div>
              <?php include('form_actions.php'); ?>
            </div>
          <?php endforeach; ?>

          <?php foreach ($toDoListsChecked as $toDoList) : ?>
            <div class="toDo checked" id="toDo_<?= $toDoList['id'] ?>">
              <!-- Clickable area for the checklist -->
              <div class="checklist-area" onclick="toggleCheckList(<?= $toDoList['id'] ?>)">
                <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" id="checkList" style="background: none;">
                  <circle cx="20" cy="20" r="19.5" fill="black" stroke="#C5C4C4" />
                </svg>
              </div>

              <!-- Clickable area for the rest of the content -->
              <div class="text" onclick="editToDo(<?= $toDoList['id'] ?>)">
                <div class="toDo-label">
                  <?= $toDoList['label'] ?>
                </div>
                <div class="toDo-text">
                  <?= $toDoList['content'] ?>
                </div>
              </div>
              <?php include('form_actions.php'); ?>
            </div>
          <?php endforeach; ?>

        <?php else : ?>
          <!-- Display a message or take appropriate action if there are no to-do items. -->
        <?php endif; ?>

      </div>
    </div>
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editModalLabel">Edit To-Do Item</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <label for="editContent" class="form-label">Content:</label>
            <input type="text" class="form-control" id="editContent">
            <label for="editLabel" class="form-label">Label:</label>
            <input type="text" class="form-control" id="editLabel">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" onclick="saveChanges()">Save changes</button>
          </div>
        </div>
      </div>
    </div>

  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</body>

</html>