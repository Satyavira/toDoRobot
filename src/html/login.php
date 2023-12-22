<?php 
session_start();

require "db.php";

if(isset($_SESSION["login"])) {
    header("Location: ./toDo.php");
    exit;
}

if(isset($_POST["login"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $query = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        if(password_verify($password, $row["password"])) {
            $_SESSION["login"] = true;
            $_SESSION["id_user"] = $row["id"];
            $_SESSION["username"] = $username;
            header("Location: toDo.php");
            exit;
        } 
    } else {
        echo mysqli_error($conn); // Add this line to display any MySQL errors
    }
}
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>ToDoRobot</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
      crossorigin="anonymous"
    />
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="shortcut icon" href="../img/logo.png" type="image/x-icon">
  </head>

  <body>
    <div id="container">
      <div id="left">
        <div id="welcome">Welcome to ToDoRobot</div>
        <br />
        <div id="get">Get started - it's free. No credit card needed</div>
        <h2 class="login-heading">Login to your account</h2>
        <div class="login-method">
          <form action="" method="POST" class="login-form">
            <input
              type="text"
              class="form-control username-input"
              id="username"
              name="username"
              placeholder="Username" />

            <input
              type="password"
              class="password-input"
              id="password"
              name="password"
              placeholder="Password" />

            <button type="submit" name="login" class="login-button">
              Login
            </button>
            <span class="sign-up"
              ><a href="./signup.php">Don't have an account?</a></span
            >
          </form>
        </div>
      </div>
      <span id="right">
        <img src="../img/logo.png" alt="Logo" id="loginLogo" />
      </span>
    </div>

    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
      crossorigin="anonymous"
    ></script>
  </body>
</html>
