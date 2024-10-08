<html>
<head>
    <title>Create New Combo</title>
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

        // Function to log the creation of a new combo
        function logComboCreation($username, $main, $side, $drink, $discount) {
            $logFile = 'combo_creation_log.txt'; // Log file location
            $currentDate = date('Y-m-d H:i:s');
            $logMessage = "User: $username created a new combo with Main: $main, Side: $side, Drink: $drink, Discount: $discount% at $currentDate\n";

            // Append the log message to the log file
            file_put_contents($logFile, $logMessage, FILE_APPEND);
        }
    ?>

    Active User: <b style="text-align: left;"><?php echo $_SESSION['username']; ?></b>
    <center><h1>Add Combo for Discount</h1>

    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <table>
            <tr>
                <td><b><label>Main Dish</label></b></td>
                <td><input type="text" name="main_dish" size="100%" placeholder="Enter Main Dish name"></td>
            </tr>
            <tr>
                <td><b><label>Side Dish</label></b></td>
                <td><input type="text" name="side_dish" size="100%" placeholder="Enter Side Dish name"></td>
            </tr>
            <tr>
                <td><b><label>Drinks</label></b></td>
                <td><input type="text" name="drinks" size="100%" placeholder="Enter Drinks name"></td>
            </tr>
            <tr>
                <td><b><label>Discount (in %)</label></b></td>
                <td><input type="number" name="discount" class="payment" size="100%"></td>
            </tr>
        </table>
        <input type="submit" class="btn" name="add_combo" value="Submit" size="100%">

        <?php
        if(isset($_POST['add_combo'])) {
            $main = $_POST['main_dish'];
            $side = $_POST['side_dish'];
            $drink = $_POST['drinks'];
            $discount = $_POST['discount'];
            $sessionUser = $_SESSION['username'];

            // Add to DB
            $conn = mysqli_connect("localhost", "root", "","dbresto","3307") or die("Unable to connect! ".mysqli_error());
            mysqli_select_db($conn, "dbresto");

            // Add combo to db
            $addString = "INSERT INTO `tblcombo` (`main`, `side`, `drink`, `discount`) VALUES ('$main','$side','$drink', $discount)";
            mysqli_query($conn, $addString);

            // Add combo to XML
            if(file_exists("combo.xml")) {
                $combos = simplexml_load_file('combo.xml');
                $combo = $combos->addChild('dish');
                $combo->addChild('main', $main);
                $combo->addChild('sides', $side);
                $combo->addChild('drink', $drink);
                $combo->addChild('discount', $discount);
                file_put_contents('combo.xml', $combos->asXML());
            }

            // Log the combo creation
            logComboCreation($sessionUser, $main, $side, $drink, $discount);

            echo "<p style='color:green'><b>Combo added successfully!<b></p>";
        }
        ?>
    </form><br>

    <p><a href='admin.php'><button class='btn'>Back</button></a></p>
    <a href='logout.php'><button class='btn'>Log Out</button></a><br><br>
</div>

</body>
</html>