<?php
    session_start(); // Start the session

    // Load values from list.txt and create a hashset (associative array) for fast lookups
    $listFile = 'redacted.txt';
    $list = [];

    if (file_exists($listFile)) {
        $lines = file($listFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $list[trim($line)] = true; // Use values as keys for fast lookups
        }
    } else {
        echo "<p>Error: List file not found.</p>";
        exit();
    }

    // Check if form was submitted
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Get user input and sanitize it
        $input = trim($_POST["userInput"]);
        $input = htmlspecialchars($input);

        // Check for match in the hashset
        if (isset($list[$input])) {
            header("Location: index.php");
            exit();
        } else {
            // Set session variable to allow access to result.php
            $_SESSION['allowed'] = true;
            // Redirect to result.php
            header("Location: result.php?input=" . urlencode($input));
            exit();
        }
    }
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h1>Home Page</h1>
    <form method="post" action="">
        <label for="userInput">Enter a value:</label><br>
        <input type="text" id="userInput" name="userInput" required>
        <br><br>
        <input type="submit" name="submit" value="Check">
    </form>
</body>
</html>
