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
        error_reporting(E_ERROR | E_PARSE);
        session_start();

         //add to DB
         $conn = mysqli_connect("localhost", "root", "","dbresto","3307") or die("Unable to connect! ".mysqli_error());
         mysqli_select_db($conn, "dbresto");
 
          // Check if session is started and user is logged in
         if (!isset($_SESSION['username'])) {
         header("Location: login.php");
         exit;
         }
 
         // Retrieve user information (including password for verification)
         $username = $_SESSION['username'];
         $stmt = $conn->prepare("SELECT password, role FROM users WHERE username = ?");
         $stmt->bind_param("s", $username);
         $stmt->execute();
         $stmt->store_result();
 
         if ($stmt->num_rows == 1) {
             $stmt->bind_result($hashedPassword, 
         $role);
             $stmt->fetch();
 
             // Check user role (assuming 'admin' role has access)
             if ($role !== 'admin') {
             header("Location: login.php"); // Redirect to non-admin page
             exit;
             }
         } else {
             // Handle user not found or invalid role
             errorWindow("Invalid user.", "Back");
             exit;
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

            //add to DB
            $conn = mysqli_connect("localhost", "root", "","dbresto","3307") or die("Unable to connect! ".mysqli_error());
            mysqli_select_db($conn, "dbresto");

            // Check if current password matches
            $passwordSelect = "SELECT password FROM `tbladmin` WHERE username='$sessionUser'";
            $passwordQuery = mysqli_query($conn, $passwordSelect);
            $passwordResult = mysqli_fetch_assoc($passwordQuery);

            if($passwordResult['password'] != $password) { // current password incorrect
                echo "<p style='color:red'><b>The current password you have entered does not match your account's password. Please try again.<b></p>";
            } else if($newPassword !== $confNewPassword) { // new password and confirm new password don't match
                echo "<p style='color:red'><b>New password does not match new password confirmation. Please try again.<b></p>";
            } else {
                // Update password
                $updatePass = "UPDATE `tbladmin` SET `password`='$newPassword' WHERE `username`='$sessionUser'";
                mysqli_query($conn, $updatePass);

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