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
        error_reporting(E_ERROR | E_PARSE);
        session_start();

        if(!isset($_SESSION['username'], $_SESSION['password'])) {
            errorWindow("No logged in user detected.", "Log In");
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
            //add to DB
            $conn = mysqli_connect("localhost", "root", "","dbresto","3307") or die("Unable to connect! ".mysqli_error());
            mysqli_select_db($conn, "dbresto");

            // Add dish to db
            $addString = "INSERT INTO `tblcombo` (`main`, `side`, `drink`, `discount`) VALUES ('$main','$side','$drink', $discount)";
            mysqli_query($conn, $addString);

            //add to xml
            if(file_exists("combo.xml")){
                    $combos = simplexml_load_file('combo.xml');
                    $combo = $combos->addChild('dish');
                    $combo->addChild('main', $main);
                    $combo->addChild('sides', $side);
                    $combo->addChild('drink', $drink);
                    $combo->addChild('discount', $discount);
                    file_put_contents('combo.xml', $combos->asXML());
                }
                

        
            echo "<p style='color:green'><b>Dish added successfully!<b></p>";
        }
        ?>
    </form><br>

    <p><a href='admin.php'><button class='btn'>Back</button></a></p>
    <a href='logout.php'><button class='btn'>Log Out</button></a><br><br>
</div>

</body>
</html>