<?php
session_start();

// Redirect to login page after 5 seconds
header("refresh:5;url=login.php");

// Clear login attempts session
unset($_SESSION['login_attempts']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Timeout</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }
        .message {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="message">
        <h2>HOY TIMEOUT</h2>
        <p>AYAN KASI YOU SHOULD REMEMBER YOU USERNAME AND PASSWORD</p>
        <p>Please wait for a moment pls...</p>
    </div>
</body>
</html>
