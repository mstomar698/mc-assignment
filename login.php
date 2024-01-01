<?php
session_start();


if (isset($_SESSION["authenticated"]) && $_SESSION["authenticated"]) {
    
    header("Location: list.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
</head>
<body>

<?php

if (isset($_SESSION["authenticated"]) && $_SESSION["authenticated"]) {
    echo '<nav><a href="list.php">Movie</a></nav>';
}
?>

<h2>Login</h2>

<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    
    $username = $_POST["username"];
    $password = $_POST["password"];

    
    $conn = new mysqli("localhost", "root", "", "cinemark_db");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM User WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        
        $user = $result->fetch_assoc();

        
        $roleQuery = "SELECT role FROM Role WHERE username = '$username'";
        $roleResult = $conn->query($roleQuery);

        if ($roleResult->num_rows > 0) {
            $role = $roleResult->fetch_assoc()["role"];

            $_SESSION["authenticated"] = true;
            $_SESSION["username"] = $user["username"];
            $_SESSION["role"] = $role;

            
            header("Location: list.php");
            exit();
        } else {
            echo "<p style='color: red;'>Invalid username or password</p>";
        }
    } else {
        echo "<p style='color: red;'>Invalid username or password</p>";
    }

    $conn->close();
}
?>

<form action="login.php" method="post">
    <label for="username">Username:</label>
    <input type="text" name="username" required><br>

    <label for="password">Password:</label>
    <input type="password" name="password" required><br>

    <input type="submit" value="Login">
</form>

</body>
</html>
