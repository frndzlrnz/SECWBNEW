<html>
<head>
    <title>Add Menu Item</title>
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
    <center><h1>Add Menu Item</h1>

    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <table>
            <tr>
                <td><b><label>Dish Name</label></b></td>
                <td><input type="text" name="dish_name" size="100%" placeholder="Enter dish name"></td>
            </tr>
            <tr>
            <td><b><label>Image</label></b></td>
                <td><input type="text" name="image_name" size="100%" placeholder="Enter image link"></td>
            </tr>
            <tr>
                <td><b><label>Category</label></b></td>
                <td>
                    <input type="radio" name="category" value="Mains"><label for="Mains"> Mains</label>
                    <input type="radio" name="category" value="Sides"><label for="Sides"> Sides</label>
                    <input type="radio" name="category" value="Drink"><label for="Drink"> Drink</label>
                </td>
            </tr>
            <tr>
                <td><b><label>Price (PHP)</label></b></td>
                <td><input type="number" name="price" class="payment" placeholder="0.00" ></td>
            </tr>
            <tr>
                <td><b><label>Quantity</label></b></td>
                <td><input type="number" name="quantity" class="quantity" placeholder="0"></td>
            </tr>
        </table>
        <input type="submit" class="btn" name="add_submit" value="Add Dish">

        <?php
        if(isset($_POST['add_submit'])) {
            $name = $_POST['dish_name'];
            $group = $_POST['category'];
            $price = $_POST['price'];
            $quantity = $_POST['quantity'];
            $image = $_POST['image_name'];

            //add to DB
            $conn = mysqli_connect("localhost", "root", "","dbresto","3307") or die("Unable to connect! ".mysqli_error());
            mysqli_select_db($conn, "dbresto");

            //get id
            $numQuery = mysqli_query($conn, "SELECT MAX(id)+1 AS num FROM dbresto.tblfood;");
                        while($numResult = mysqli_fetch_assoc($numQuery)){
                            $aNum = $numResult['num'];
                        }

            // Add dish to db
            $addString = "INSERT INTO `tblfood` (`id`, `name`, `group`, `price`, `quantity`, `image`) VALUES ('$aNum','$name', '$group', $price, $quantity, '$image')";
            mysqli_query($conn, $addString);

            //add to xml
            if(file_exists("dishes.xml")){
                    $dishes = simplexml_load_file('dishes.xml');
                    $dish = $dishes->addChild('dish');
                    $dish->addChild('id', $aNum);
                    $dish->addChild('name', $name);
                    $dish->addChild('group', $group);
                    $dish->addChild('price', $price);
                    $dish->addChild('quantity', $quantity);
                    $dish->addChild('image', $image);
                    file_put_contents('dishes.xml', $dishes->asXML());
                }
                

            

            echo "<p style='color:green'><b>Dish added successfully!<b></p>";
        }
        ?>
    </form><br>

        <p><a href='admin.php'><button class='btn'>Back</button></a></p>
        <a href='logout.php'><button class='btn'>Log Out</button></a><br><br>
    </center>
</div>

</body>
</html>