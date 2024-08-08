<html>
<head>
    <title>Add User Account</title>
    <link rel="stylesheet" href="login_style.css">
</head>
<body>

<div class="login">
    <?php
        // Initialize
        include("functions.php");
        error_reporting(E_ERROR | E_PARSE);
        session_start();

        if(!isset($_SESSION['username'], $_SESSION['password'])) {
            errorWindow("No logged in user detected.", "Log In");
        }

        // Function to log the creation of a new user
        function logUserCreation($username) {
            $logFile = 'user_creation_log.txt'; // Log file location
            $currentDate = date('Y-m-d H:i:s');
            $logMessage = "User: $username was created at $currentDate\n";

            // Append the log message to the log file
            file_put_contents($logFile, $logMessage, FILE_APPEND);
        }
    ?>

    <p>Active User: <b style="text-align: left;"><?php echo $_SESSION['username']; ?></b></p>
    <center>

    <h1>Add User Account</h1>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <table>
            <tr><td colspan="2">NOTE: Username cannot be changed after being created.</td></tr>
            <tr>
                <td><b><label>Username</label></b></td>
                <td><input type="text" name="username" size="100%" placeholder="Enter Username"></td>
            </tr>
            <tr>
                <td><b><label>Password</label></b></td>
                <td><input type="password" name="password" size="100%" placeholder="Enter Password"></td>
            </tr>
            <tr>
                <td><b><label>Confirm Password</label></b></td>
                <td><input type="password" name="conf_password" size="100%" placeholder="Repeat Password"></td>
            </tr>
        </table>
        <input type="submit" class="btn" name="add_submit" value="Add User">

        <?php
        if(isset($_POST['add_submit'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $confPassword = $_POST['conf_password'];

            // Add to DB
            $conn = mysqli_connect("localhost", "root", "","dbresto","3307") or die("Unable to connect! ".mysqli_error());
            mysqli_select_db($conn, "dbresto");

            // Check if username exists
            $usernameSelect = "SELECT username FROM `tbladmin` WHERE username='$username'";
            $usernameQuery = mysqli_query($conn, $usernameSelect);

            if(mysqli_num_rows($usernameQuery) > 0) { // username already exists
                echo "<p style='color:red'><b>Username already exists!<b></p>";
            } else if($password !== $confPassword) { // password and confirm password don't match
                echo "<p style='color:red'><b>Passwords do not match. Please try again.<b></p>";
            } else {
                // Add user to DB
                $addString = "INSERT INTO `tbladmin` (`username`, `password`) VALUES ('$username','$password')";
                mysqli_query($conn, $addString);

                // Log the creation of the new user
                logUserCreation($username);

                echo "<p style='color:green'><b>User added successfully!<b></p>";
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
