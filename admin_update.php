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

    Active User: <b style="text-align: left;"><?php echo $_SESSION['username']; ?></b>
    <center><h1>Update Menu Item</h2>

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
                    
                    //update dishes.xml
                    foreach($xml->dish as $dish){
                        //if id in the list matches the selected, update
                        if($dish->id == $selectedDish){
                            $name = $_POST['dish_name'];
                            $group = $_POST['category'];
                            $price = $_POST['price'];
                            $quantity = $_POST['quantity'];

                            $dish->name = $name;
                            $dish->group = $group;
                            $dish->price = $price;
                            $dish->quantity=$quantity;

                            file_put_contents('dishes.xml', $xml->asXML());

            //add to DB
            $conn = mysqli_connect("localhost", "root", "","dbresto","3307") or die("Unable to connect! ".mysqli_error());
            mysqli_select_db($conn, "dbresto");

                            $updateString = "UPDATE `tblfood` SET `name` = '$name', `group` = '$group', price = '$price', quantity = '$quantity' WHERE (id = '$selectedDish')";
                            mysqli_query($conn,$updateString);

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