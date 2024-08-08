<html>
<head>
    <title>Update Menu Item</title>
    <link rel="stylesheet" href="update_style.css">
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

        // Function to log the update of a menu item
        function logMenuUpdate($username, $selectedDish, $oldName, $oldGroup, $oldPrice, $oldQuantity, $newName, $newGroup, $newPrice, $newQuantity) {
            $logFile = 'menu_update_log.txt'; // Log file location
            $currentDate = date('Y-m-d H:i:s');
            $logMessage = "User: $username updated dish ID: $selectedDish on $currentDate. Old Values - Name: $oldName, Category: $oldGroup, Price: $oldPrice, Quantity: $oldQuantity. New Values - Name: $newName, Category: $newGroup, Price: $newPrice, Quantity: $newQuantity\n";

            // Append the log message to the log file
            file_put_contents($logFile, $logMessage, FILE_APPEND);
        }
    ?>

    Active User: <b style="text-align: left;"><?php echo $_SESSION['username']; ?></b>
    <center><h1>Update Menu Item</h1>

    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <table>
        <td><b><label>Select Dish to Update</label></b></td>
        
        <td><select id="selectdish" name="selectdish">
            <?php
                $xml = simplexml_load_file('dishes.xml');
                foreach($xml->dish as $dish){
                    echo('<option value="'.$dish->id.'">'.$dish->name.'</option>');
                }
            ?>
        </select>
        </td></tr>
        <tr>
            <td><b><label>Dish Name</label></b></td>
            <td><input type="text" name="dish_name" size="100%" placeholder="Enter dish name"></td>
        </tr>
        <tr>
            <td><b><label>Category</label></b></td>
            <td>
                <input type="radio" name="category" value="Mains" ><label for="Mains"> Mains</label>
                <input type="radio" name="category" value="Sides" ><label for="Sides"> Sides</label>
                <input type="radio" name="category" value="Drink" ><label for="Drink"> Drink</label>
            </td>
        </tr>
        <tr>
            <td><b><label>Price (PHP)</label></b></td>
            <td><input type="number" name="price" class="payment" placeholder="0.00" ></td>
        </tr>
        <tr>
            <td><b><label>Quantity</label></b></td>
            <td><input type="number" name="quantity" class="quantity" placeholder="0" ></td>
        </tr>
    </table>    
    <input type="submit" class="btn1" name="update_submit" value="Update Dish">
        <?php
            if(isset($_POST['update_submit'])){
                
                if(file_exists("dishes.xml")){
                    $selectedDish = $_POST['selectdish'];
                    
                    // Load XML
                    $xml = simplexml_load_file('dishes.xml');
                    
                    foreach($xml->dish as $dish){
                        if($dish->id == $selectedDish){
                            // Store old values for logging
                            $oldName = $dish->name;
                            $oldGroup = $dish->group;
                            $oldPrice = $dish->price;
                            $oldQuantity = $dish->quantity;

                            // Get new values from form
                            $newName = $_POST['dish_name'];
                            $newGroup = $_POST['category'];
                            $newPrice = $_POST['price'];
                            $newQuantity = $_POST['quantity'];

                            // Update XML
                            $dish->name = $newName;
                            $dish->group = $newGroup;
                            $dish->price = $newPrice;
                            $dish->quantity = $newQuantity;

                            file_put_contents('dishes.xml', $xml->asXML());

                            // Update DB
                            $conn = mysqli_connect("localhost", "root", "","dbresto","3307") or die("Unable to connect! ".mysqli_error());
                            mysqli_select_db($conn, "dbresto");

                            $updateString = "UPDATE `tblfood` SET `name` = '$newName', `group` = '$newGroup', `price` = '$newPrice', `quantity` = '$newQuantity' WHERE `id` = '$selectedDish'";
                            mysqli_query($conn, $updateString);

                            // Log the update
                            $sessionUser = $_SESSION['username'];
                            logMenuUpdate($sessionUser, $selectedDish, $oldName, $oldGroup, $oldPrice, $oldQuantity, $newName, $newGroup, $newPrice, $newQuantity);

                            echo "<p style='color:green'><b>Dish updated!<b></p>";
                        }
                    }
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
