<!DOCTYPE html>
<html>
<head>
    <title>Delete Menu Item</title>
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

        if(!isset($_SESSION['username'], $_SESSION['password'])) {
            errorWindow("No logged in user detected.", "Log In");
        }

        // Function to log the deletion of a menu item
        function logMenuDeletion($username, $dishId, $dishName) {
            $logFile = 'menu_deletion_log.txt'; // Log file location
            $currentDate = date('Y-m-d H:i:s');
            $logMessage = "User: $username deleted dish ID: $dishId (Name: $dishName) on $currentDate\n";

            // Append the log message to the log file
            file_put_contents($logFile, $logMessage, FILE_APPEND);
        }
    ?>

    Active User: <b style="text-align: left;"><?php echo $_SESSION['username']; ?></b>
    <center><h1>Delete Menu Item</h1></center>

    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <table>
            <tr>
                <td><b><label>Select Dish to Delete</label></b></td>
                <td>
                    <select id="selectdish" name="selectdish">
                        <?php
                            $xml = simplexml_load_file('dishes.xml');
                            foreach($xml->dish as $dish){
                                echo('<option value="'.$dish->id.'">'.$dish->name.'</option>');
                            }
                        ?>
                    </select>
                </td>
            </tr>
        </table>
        <input type="submit" class="btn1" name="delete_submit" value="Delete Dish">
    </form>
    <?php
        if(isset($_POST['delete_submit'])){
            if(file_exists("dishes.xml")){
                $selectedDish = $_POST['selectdish'];
                $xml = simplexml_load_file('dishes.xml');
        
                $dom = new DOMDocument('1.0', 'utf-8');
                $dom->preserveWhiteSpace = false;
                $dom->formatOutput = true;
                $dom->loadXML($xml->asXML());
        
                $root = $dom->documentElement;
                $dishName = '';
                foreach($root->childNodes as $dishNode){
                    if($dishNode->nodeName === 'dish'){
                        $idNode = $dishNode->getElementsByTagName('id')->item(0);
                        $nameNode = $dishNode->getElementsByTagName('name')->item(0);
                        if($idNode && $idNode->nodeValue == $selectedDish){
                            $dishName = $nameNode->nodeValue;
                            $root->removeChild($dishNode);
                            break;
                        }
                    }
                }
        
                // Save the modified XML back to the file
                $dom->save('dishes.xml');
        
                // Delete from DB
                $conn = mysqli_connect("localhost", "root", "","dbresto","3307") or die("Unable to connect! ".mysqli_error());
                mysqli_select_db($conn, "dbresto");
        
                $deleteString = "DELETE FROM `tblfood` WHERE id = '$selectedDish'";
                if(mysqli_query($conn, $deleteString)){
                    echo "<p style='color:red'><b>Dish deleted!</b></p>";

                    // Log the deletion
                    $sessionUser = $_SESSION['username'];
                    logMenuDeletion($sessionUser, $selectedDish, $dishName);
                } else {
                    echo "<p style='color:red'><b>Error deleting dish: " . mysqli_error($conn) . "</b></p>";
                    echo "<p style='color:red'><b>Query: $deleteString</b></p>";
                }
            }
        }
    ?>
    <br>
    <p><a href='admin.php'><button class='btn'>Back</button></a></p>
    <a href='logout.php'><button class='btn'>Log Out</button></a><br><br>
    </center>
</div>

</body>
</html>