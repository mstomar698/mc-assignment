<?php

session_start();


if (!isset($_SESSION["authenticated"]) || $_SESSION["role"] !== "admin") {
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


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = mysqli_real_escape_string($conn, $_POST["title"]);
    $description = mysqli_real_escape_string($conn, $_POST["description"]);
    $director = mysqli_real_escape_string($conn, $_POST["director"]);
    $release_year = mysqli_real_escape_string($conn, $_POST["release_year"]);
    $budget = mysqli_real_escape_string($conn, $_POST["budget"]);
    $runtime = mysqli_real_escape_string($conn, $_POST["runtime"]);
    $rating = mysqli_real_escape_string($conn, $_POST["rating"]);
    $genre = mysqli_real_escape_string($conn, $_POST["genre"]);

    
    $insertSql = "INSERT INTO Movie (title, description, director, release_year, budget, runtime, rating, genre) 
                  VALUES ('$title', '$description', '$director', '$release_year', '$budget', '$runtime', '$rating', '$genre')";

    if ($conn->query($insertSql) === TRUE) {
        
        header("Location: list.php");
        exit();
    } else {
        
        echo "<p style='color: red;'>Error adding movie: " . $conn->error . "</p>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Movie</title>
</head>
<body>

    <nav>
        <a href="list.php">Movie</a>
    </nav>

    <h2>Add Movie</h2>

    <form action="add.php" method="post">
        <label for="title">Title:</label>
        <input type="text" name="title" required><br>

        <label for="description">Description:</label>
        <textarea name="description" required></textarea><br>

        <label for="director">Director:</label>
        <input type="text" name="director" required><br>

        <label for="release_year">Release Year:</label>
        <input type="text" name="release_year" required><br>

        <label for="budget">Budget:</label>
        <input type="text" name="budget" required><br>

        <label for="runtime">Runtime:</label>
        <input type="text" name="runtime" required><br>

        <label for="rating">Rating:</label>
        <input type="text" name="rating" required><br>

        <label for="genre">Genre:</label>
        <input type="text" name="genre" required><br>

        <input type="submit" value="Add Movie">
    </form>

</body>
</html>
