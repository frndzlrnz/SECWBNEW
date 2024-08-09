<html>
<head>
    <title>Change Password</title>
    <link rel="stylesheet" href="login_style.css">
</head>
<body>

<div class="login">
    <?php
        // Initialize
        include("functions.php");
        // Define debug flag (set to true for development)
    define('DEBUG', true); // Change to false for production

    // Error reporting level based on debug flag
    if (DEBUG) {
        error_reporting(E_ALL); // Show all errors with stack trace
    } else {
        error_reporting(E_ERROR | E_PARSE); // Show only fatal errors
    }
    session_set_cookie_params(1800);
    session_start();


         //add to DB
         $conn = mysqli_connect("localhost", "root", "","dbresto","3307") or die("Unable to connect! ".mysqli_error());
         mysqli_select_db($conn, "dbresto");
 
         if(!isset($_SESSION['username'], $_SESSION['password'])) {
            errorWindow("No logged in user detected.", "Log In");
        }

        // Function to log the password change
        function logPasswordChange($username) {
            $logFile = 'password_change_log.txt'; // Log file location
            $currentDate = date('Y-m-d H:i:s');
            $logMessage = "User: $username changed their password at $currentDate\n";

            // Append the log message to the log file
            file_put_contents($logFile, $logMessage, FILE_APPEND);
        }
    ?>

    <p>Active User: <b style="text-align: left;"><?php echo $_SESSION['username']; ?></b></p>
    <center>

    <h1>Change Password</h1>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <table>
            <tr><td colspan="2">NOTE: Username cannot be changed after being created.</td></tr>
            <tr>
                <td><b><label>Current Password</label></b></td>
                <td><input type="password" name="password" size="100%" placeholder="Enter Password"></td>
            </tr>
            <tr>
                <td><b><label>New Password</label></b></td>
                <td><input type="password" name="new_password" size="100%" placeholder="New Password"></td>
            </tr>
            <tr>
                <td><b><label>Confirm New Password</label></b></td>
                <td><input type="password" name="conf_new_password" size="100%" placeholder="Repeat New Password"></td>
            </tr>
        </table>
        <input type="submit" class="btn" name="pass_submit" value="Change Password" style="width:200px;">

        <?php
        if(isset($_POST['pass_submit'])) {
        $sessionUser = $_SESSION['username'];
        $password = $_POST['password'];
        $newPassword = $_POST['new_password'];
        $confNewPassword = $_POST['conf_new_password'];

        // Add to DB
        $conn = mysqli_connect("localhost", "root", "","dbresto","3307") or die("Unable to connect! ".mysqli_error());
        mysqli_select_db($conn, "dbresto");

        // Check if current password matches
        $passwordSelect = "SELECT password FROM `users` WHERE username='$sessionUser'";
        $passwordQuery = mysqli_query($conn, $passwordSelect);
        $passwordResult = mysqli_fetch_assoc($passwordQuery);

        // Verify password using password_verify()
        if (!password_verify($password, $passwordResult['password'])) {
        echo "<p style='color:red'><b>The current password you have entered does not match your account's password. Please try again.<b></p>";
        } else if($newPassword !== $confNewPassword) { // new password and confirm new password don't match
        echo "<p style='color:red'><b>New password does not match new password confirmation. Please try again.<b></p>";
        } else {
        // Hash the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update password
        $updatePass = "UPDATE `users` SET `password`='$hashedPassword' WHERE `username`='$sessionUser'";
        mysqli_query($conn, $updatePass);

        // Log the password change
        logPasswordChange($sessionUser);

        echo "<p style='color:green'><b>Password changed successfully!<b></p>";
        }
        }
?>
    </form><br>

        <p><a href='admin.php'><button class='btn'>Back</button></a></p>
        <a href='logout.php'><button class='btn'>Log Out</button></a><br><br>
    </center>
</div>

</body>
</html>