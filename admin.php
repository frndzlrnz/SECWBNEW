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

            // Open db to select from users
            //add to DB
            $conn = mysqli_connect("localhost", "root", "","dbresto","3307") or die("Unable to connect! ".mysqli_error());
            mysqli_select_db($conn, "dbresto");

            // Check connection
            if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
            }

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $username = $_POST['username'];
                $password = $_POST['password'];
                $recaptchaResponse = $_POST['g-recaptcha-response'];
            
                // Verify the CAPTCHA response
                $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$recaptchaSecret&response=$recaptchaResponse");
                $responseKeys = json_decode($response, true);
            
                if (intval($responseKeys["success"]) !== 1) {
                    $error = "Please complete the CAPTCHA.";
                } else {
                    // Prepare and execute SQL statement to retrieve hashed password, salt, and role for the provided username
                    $stmt = $conn->prepare("SELECT password, salt, role FROM users WHERE username = ?");
                    $stmt->bind_param("s", $username);
                    $stmt->execute();
                    $stmt->store_result();
            
                    if ($stmt->num_rows == 1) {
                        $stmt->bind_result($hashedPassword, $salt, $role);
                        $stmt->fetch();
            
                        // Verify the provided password against the hashed password from the database
                        $saltedPassword = $password . $salt;
                        if (password_verify($saltedPassword, $hashedPassword)) {
                            // Password is correct, set session variables
                            $_SESSION['loggedin'] = true;
                            $_SESSION['username'] = $username;
                            $_SESSION['role'] = $role;
            
                            // Redirect based on role
                            if ($role === 'user') {
                                header("Location: main.php");
                            } else {
                                header("Location: admin.php");
                            }
                            exit;
                        } else {
                            $error = "Invalid username or password.";
                        }
                    } else {
                        $error = "Invalid username or password.";
                    }
            
                    $stmt->close();
                }
            }
            
            $conn->close();
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