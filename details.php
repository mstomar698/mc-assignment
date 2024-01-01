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


if (isset($_GET["movie_id"])) {
    $movie_id = $_GET["movie_id"];
    $sql = "SELECT * FROM Movie WHERE movie_id = $movie_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        
        echo "<nav style='background-color: #333; color: white; padding: 10px; margin-bottom: 10px;'>";
        echo "<a href='list.php'>Movie</a>";
        echo "</nav>";

        echo "<img src='https://via.placeholder.com/52x52' alt='{$row['title']}'><br>";

        echo "Title: {$row['title']}<br>";
        echo "Director: {$row['director']}<br>";
        echo "Description: {$row['description']}<br>";
        echo "Release Year: {$row['release_year']}<br>";
        echo "Budget: {$row['budget']}<br>";
        echo "Runtime: {$row['runtime']}<br>";
        echo "Rating: {$row['rating']}<br>";
        echo "Genre: {$row['genre']}<br>";

        
        if ($_SESSION["role"] === "admin") {
            echo "<form method='post'>";
            echo "<input type='hidden' name='delete_movie' value='{$row['movie_id']}'>";
            echo "<input type='submit' value='Delete'>";
            echo "</form>";

            
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_movie"])) {
                $delete_movie_id = $_POST["delete_movie"];

                
                if ($_SESSION["role"] === "admin") {
                    
                    $deleteSql = "DELETE FROM Movie WHERE movie_id = $delete_movie_id";

                    if ($conn->query($deleteSql) === TRUE) {
                        
                        
                        header("Location: list.php");
                        exit();
                    } else {
                        
                        echo "Error deleting movie: " . $conn->error;
                    }
                }
            }
        }
    } else {
        echo "Movie not found.";
    }
} else {
    echo "Movie ID not provided.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Details</title>
</head>
<body>

</body>
</html>
