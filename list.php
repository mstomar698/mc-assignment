<?php

session_start();


if (!isset($_SESSION["authenticated"]) || !$_SESSION["authenticated"]) {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cinemark_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_movie"])) {
    $movie_id = $_POST["delete_movie"];

    
    if ($_SESSION["role"] === "admin") {
        
        $deleteSql = "DELETE FROM Movie WHERE movie_id = $movie_id";

        if ($conn->query($deleteSql) === TRUE) {
            
            
            header("Location: list.php");
            exit();
        } else {
            
            echo "Error deleting movie: " . $conn->error;
        }
    }
}


if (isset($_GET['logout'])) {
    
    $_SESSION = array();

    
    session_destroy();

    
    header("Location: login.php");
    exit();
}

$sql = "SELECT * FROM Movie";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Page</title>
    <!-- Add some basic styling for the navigation bar -->
    <style>
        nav {
            background-color: #333;
            color: white;
            padding: 10px;
            margin-bottom: 10px;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin-right: 10px;
        }
    </style>
</head>
<body>

    <?php
    
    echo "<nav><a href='list.php'>Movie</a>";

    
    if ($_SESSION["role"] === "admin") {
        echo "<a href='add.php'>Add Movie</a>";
    }

    
    echo "<a href='?logout'>Logout</a>";

    echo "</nav>";
    ?>

    <h2>List of Movies</h2>

    <?php
    
    while ($row = $result->fetch_assoc()) {
        echo "<img src='https://via.placeholder.com/52x52' alt='{$row['title']}'><br>";
        echo "Title: {$row['title']}<br>";
        echo "Director: {$row['director']}<br>";

        
        if ($_SESSION["role"] === "admin") {
            echo "<form method='post'>";
            echo "<input type='hidden' name='delete_movie' value='{$row['movie_id']}'>";
            echo "<input type='submit' value='Delete'>";
            echo "</form>";
        }

        echo "<a href='details.php?movie_id={$row['movie_id']}'>Details</a><br><br>";
    }

    $conn->close();
    ?>

</body>
</html>
