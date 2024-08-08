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
            //add to DB
            $conn = mysqli_connect("localhost", "root", "","dbresto","3307") or die("Unable to connect! ".mysqli_error());
            mysqli_select_db($conn, "dbresto");

            // Checking if session hasn't started yet
            if(!isset($_SESSION['username'], $_SESSION['password'])) {
                $username = $_POST['username'];
                $password = $_POST['password'];

                // First check: if user is not logged in, hide page
                if(!isset($_POST['username'], $_POST['password'])) {
                    errorWindow("No logged in user detected.", "Log In");
                }
            
                // Check if username exists
                $usernameSelect = "SELECT username FROM `tbladmin` WHERE username='$username'";
                $usernameQuery = mysqli_query($conn, $usernameSelect);
                if(mysqli_num_rows($usernameQuery) == 0) {
                    errorWindow("Couldn't find your account. Please try again.", "Back");
                } else {
                    // Check if password is correct
                    $passwordSelect = "SELECT password FROM `tbladmin` WHERE username='$username'";
                    $passwordQuery = mysqli_query($conn, $passwordSelect);
                    $passwordResult = mysqli_fetch_assoc($passwordQuery);
                    if($passwordResult['password'] != $password) {
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