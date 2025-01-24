<?php
session_start(); // Start the session

// Check if access is allowed
if (!isset($_SESSION['allowed']) || $_SESSION['allowed'] !== true) {
    // Access denied - redirect to index.php
    header("Location: index.php");
    exit();
}

// Unset the session variable to prevent repeated access
unset($_SESSION['allowed']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Success</title>
</head>
<body>
    <h1>Success</h1>
    <?php
    // Display the input passed from index.php
    if (isset($_GET['input'])) {
        $input = htmlspecialchars($_GET['input']);
        echo "<p>'$input'</p>";
    } else {
        echo "<p>No input received.</p>";
    }
    ?>
    <button type='button' ><a href="index.php">Logout</a></button>
</body>
</html>
