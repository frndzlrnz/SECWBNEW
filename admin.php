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


    $errorMessage = null; // Initialize error message variable
    try {
        // Open db to select from tbladmin
        $conn = mysqli_connect("localhost", "root", "", "dbresto", "3307");
        if (!$conn) {
            throw new Exception("Database connection failed: " . mysqli_connect_error());
        }
        mysqli_select_db($conn, "dbresto");
    // Function to write log
    function writeLog($message) {
        $logFile = 'login_attempts.log';
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "$timestamp - $message" . PHP_EOL;
        file_put_contents($logFile, $logMessage, FILE_APPEND);
    }

      // Checking if session hasn't started yet
      if(!isset($_SESSION['username'], $_SESSION['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $role = $_POST['role'];

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
            if($role != 'admin') {
                errorWindow("Unauthorized Access", "Back");
            }
        }

        // Close db
        mysqli_close($conn);
        
        // Once checks are done, set variables for session
        $_SESSION['username'] = $username;
        $_SESSION['password'] = $password;
    } else {
        // Add reCAPTCHA validation here
        if (!isset($_SESSION['recaptcha_verified'])) {
        if(isset($_POST['g-recaptcha-response'])) {
          $secret = '6LdpOPMpAAAAALqmJKMKcVtPmVLMwAtO0icKthkT'; // Replace with your actual reCAPTCHA secret key
          $response = $_POST['g-recaptcha-response'];
          $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$response";

          $verifyResponse = file_get_contents($url);
          $responseData = json_decode($verifyResponse);
          if ($responseData->success){
          $_SESSION['recaptcha_verified'] = true;
          } else {
            errorWindow("Failed reCAPTCHA verification. Please try again.", "Back");
            exit();
          }
        } else {
          errorWindow("Please verify you are not a robot.", "Back");
          exit();
        }
    }
}
} catch (Exception $e) {
    if (DEBUG) {
        error_log("Error: " . $e->getMessage() . "\n" . $e->getTraceAsString());
        $errorMessage = "An error occurred. Please try again later.";
    } else {
        $errorMessage = "An error occurred. Please try again later.";
    }
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