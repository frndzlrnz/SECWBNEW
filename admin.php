<html>
<head>
    <title>Admin Page</title>
    <link rel="stylesheet" href="login_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <div class="login">
    <?php
    // Initialize
    include("functions.php");
    error_reporting(E_ERROR | E_PARSE);
    session_start();

    // Open db to select from tbladmin
    $conn = mysqli_connect("localhost", "root", "", "dbresto", "3307") or die("Unable to connect! ".mysqli_error());
    mysqli_select_db($conn, "dbresto");

    // Function to write log
    function writeLog($message) {
        $logFile = 'login_attempts.log';
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "$timestamp - $message" . PHP_EOL;
        file_put_contents($logFile, $logMessage, FILE_APPEND);
    }

    // // Check if form is submitted
    // if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //     // reCAPTCHA verification
    //     $recaptchaSecret = "6LdpOPMpAAAAALqmJKMKcVtPmVLMwAtO0icKthkT";
    //     $recaptchaResponse = $_POST['g-recaptcha-response'];

    //     $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$recaptchaSecret&response=$recaptchaResponse");
    //     $responseKeys = json_decode($response, true);

    //     if (intval($responseKeys["success"]) !== 1) {
    //         writeLog("Failed reCAPTCHA for username: $username");
    //         errorWindow("Please complete the reCAPTCHA", "Back");
    //         exit;
    //     }

    //     // Proceed with login checks if reCAPTCHA is verified
    //     $username = $_POST['username'];
    //     $password = $_POST['password'];

    //     // Check if username exists
    //     $usernameSelect = "SELECT username, salt FROM `users` WHERE username=?";
    //     $stmt = $conn->prepare($usernameSelect);
    //     $stmt->bind_param("s", $username);
    //     $stmt->execute();
    //     $stmt->store_result();

    //     if ($stmt->num_rows == 0) {
    //         writeLog("Failed login attempt for non-existing username: $username");
    //         errorWindow("Couldn't find your account. Please try again.", "Back");
    //     } else {
    //         $stmt->bind_result($username, $salt); // Retrieve username and salt
    //         $stmt->fetch();

    //         // Verify the password using password_verify
    //         if (password_verify($password, $salt)) {
    //         // Password is correct
    //         writeLog("Successful login for username: $username");
    //         $_SESSION['username'] = $username;
    //         exit;
    //         } else {
    //         writeLog("Failed login attempt for username: $username - Incorrect password");
    //         errorWindow("Wrong password. Please try again.", "Back");
    //         }
    //     }
    //     mysqli_close($conn);
    //     } else {
    //     writeLog("No login attempt detected.");
    //     errorWindow("No logged in user detected.", "Log In");
    //     }

      // Checking if session hasn't started yet
      if(!isset($_SESSION['username'], $_SESSION['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // First check: if user is not logged in, hide page
        if(!isset($_POST['username'], $_POST['password'])) {
            errorWindow("No logged in user detected.", "Log In");
        }
    
        // Check if username exists
        $usernameSelect = "SELECT username FROM `users` WHERE username='$username'";
        $usernameQuery = mysqli_query($conn, $usernameSelect);
        if(mysqli_num_rows($usernameQuery) == 0) {
            errorWindow("Couldn't find your account. Please try again.", "Back");
        } else {
            // Check if password is correct
            $passwordSelect = "SELECT password FROM `users` WHERE username='$username'";
            $passwordQuery = mysqli_query($conn, $passwordSelect);
            $passwordResult = mysqli_fetch_assoc($passwordQuery);

            $stmt = $conn->prepare("SELECT password, role FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows == 1) {
                $stmt->bind_result($hashedPassword, $role);
                $stmt->fetch();
            }
            if ($role == 'user'){
                header("Location: main.php");
            } else {
                header("Location: admin.php");
            }
            if($passwordResult['password'] != $hashedPassword) {
                errorWindow("Wrong password. Please try again.", "Back");
            }
        }

        // Close db
        mysqli_close($conn);

        // Once checks are done, set variables for session
        $_SESSION['username'] = $username;
        $_SESSION['password'] = $password;
    }
?>


        <!-- Actual admin page, if login is successful -->
        Active User: <b style="text-align: left;"><?php echo $_SESSION['username']; ?></b>
        <center>
            <p>
                <a href="admin_add.php"><button class="btn_large_admin"><i class="fa fa-plus" style="font-size: 100;"></i><br>Add Menu Item</button></a>

                <a href="admin_update.php"><button class="btn_large_admin"><i class="fa fa-edit" style="font-size: 100;"></i><br>Update Menu Item</button></a>

                <a href="admin_delete.php"><button class="btn_large_admin"><i class="fa fa-trash" style="font-size: 100;"></i><br>Delete Menu Item</button></a>

                <a href="admin_combo.php"><button class="btn_large_admin"><i class="fa fa-tag" style="font-size: 100;"></i><br>Create New Combo</button></a>
            </p>

            <p>
                <a href="admin_report.php"><button class="btn_large_admin"><i class="fa fa-clipboard" style="font-size: 100;"></i><br>Generate Report</button></a>

                <a href="admin_user.php"><button class="btn_large_admin"><i class="fa fa-user" style="font-size: 100;"></i><br>Add User Account</button></a>

                <a href="admin_password.php"><button class="btn_large_admin"><i class="fa fa-lock" style="font-size: 100;"></i><br>Change Password</button></a>
            </p>

            <br>
            <a href='logout.php'><button class='btn'>Log Out</button></a><br><br>
        </center>
    </div>

</body>
</html>