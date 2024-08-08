<html>
<head>
    <title>Generate Report</title>
    <link rel="stylesheet" href="login_style.css">
</head>
<body>

<div class="login">
    <?php
        // Initialize
        include("functions.php");
        error_reporting(E_ERROR | E_PARSE);
        session_start();
        session_set_cookie_params(1800);

         //add to DB
         $conn = mysqli_connect("localhost", "root", "","dbresto","3307") or die("Unable to connect! ".mysqli_error());
         mysqli_select_db($conn, "dbresto");
 
         if(!isset($_SESSION['username'], $_SESSION['password'])) {
            errorWindow("No logged in user detected.", "Log In");
        }
    ?>

    Active User: <b style="text-align: left;"><?php echo $_SESSION['username']; ?></b>
    <center><h2>Generate Report</h2>

    <!-- stuff -->
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <table>
        <tr>
            <td><b><label>Select Date</label></b></td>
            <td><input type="date" name="date" size="100%" required></td>
            <td><input type="submit" class="btn" name="searchbtn" value="Search"></td>
        </tr>
        <tr></tr>
    </table>

    
        <!-- <th>Total Discount Given</th> -->
        </tr>
        <?php 
              if(isset($_POST['searchbtn'])) {

                echo "<table border='1' width='70%'>";
                echo "<th>Date</th>";
                echo "<th>Total Dishes Sold</th>";      
                echo "<th>Total Earnings (PHP)</th>";
                echo "<th>Total Discount (PHP)</th>";  
    
            //add to DB
            $conn = mysqli_connect("localhost", "root", "","dbresto","3307") or die("Unable to connect! ".mysqli_error());
            mysqli_select_db($conn, "dbresto");
                $selectedDate = $_POST['date'];


                // Select matching date,  total dishes sold for that day, total earnings from DB
                $qString = "SELECT date, COUNT(name) as totaldish, SUM(total) as totalearnings, SUM(totaldiscount) as discount
                FROM tblftrans
                WHERE date = '$selectedDate'";
                $query = mysqli_query($conn, $qString);
                $result = mysqli_fetch_array($query);

                $rDate = $result['date'];
                $rTotalDish = $result['totaldish'];
                $rTotalEarnings = $result['totalearnings'];
                $rDiscount = $result['discount'];
                $dateGen = date("Y-m-d");
                $timeGen = date("h:i:sa");

                echo "<tr>"; 
                echo ("<td>". $result['date'] ."</td>");
                echo ("<td>". $result['totaldish'] ."</td>");
                echo ("<td>". $result['totalearnings'] ."</td>");
                echo ("<td>". $result['discount'] ."</td>");
                echo "</tr>"; 

                //add the report to the xml file
                if(file_exists("orders.xml")){
                    $orders = simplexml_load_file('orders.xml');
                    $order = $orders->addChild('order');
                    $order->addAttribute('date',$rDate);
                    $order->addChild('dateGenerated',$dateGen);
                    $order->addChild('timeGenerated',$timeGen);
                    $order->addChild('dishesSold',$rTotalDish);
                    $order->addChild('totalAmt',$rTotalEarnings);
                    $order->addChild('totalDiscount',$rDiscount);
                    file_put_contents('orders.xml', $orders->asXML());
                }

    
                
            }
        ?>
    </table>

    </form><br>

    <p><a href='admin.php'><button class='btn'>Back</button></a></p>
    <a href='logout.php'><button class='btn'>Log Out</button></a><br><br>
    </center>
</div>

</body>
</html>