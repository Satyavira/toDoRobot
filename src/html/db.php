<?php
$host = "localhost";
$user = "root";  // Replace with your MySQL username
$password = "php";  // Replace with your MySQL password
$dbname = "toDoRobotDatabase";
$port = "3306";  // Replace with your MySQL port (usually 3306 for MySQL)

$conn = mysqli_connect($host, $user, $password, $dbname, $port);

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
