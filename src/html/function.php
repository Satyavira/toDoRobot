<?php
require_once 'db.php';
function getLabel() {
    global $conn;
  $queryLabels = "SELECT DISTINCT label
                    FROM toDoList
                    WHERE userId LIKE ?";
  $stmtLabels = $conn->prepare($queryLabels);
  $stmtLabels->bind_param("s", $_SESSION["id_user"]);
  $stmtLabels->execute();
  $labels = $stmtLabels->get_result()->fetch_all(MYSQLI_ASSOC);
  return $labels;
}

function signUp($data)
{
    global $conn;

    $username = strtolower($data["username"]);
    $password = mysqli_real_escape_string($conn, $data["password"]);
    $passwordConfirmation = mysqli_real_escape_string($conn, $data["passwordConfirmation"]);

    $query = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_fetch_assoc($result)) {
        echo "<script>
                    alert(`Username has already been used!!!`);
                </script>";
        return false;
    }

    if ($password !== $passwordConfirmation) {
        echo "
                <script>
                    alert(`Password Confirmation Doesn't Match!!!`);
                </script>
            ";
        return false;
    }

    $password = password_hash($password, PASSWORD_DEFAULT);

    $query = "
            INSERT INTO users (username, password) 
                VALUES  ('$username', '$password'); 
        ";
    $result = mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

?>