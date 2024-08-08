<html>
<head><title>Checkout</title>
    <link rel="stylesheet" href="checkout_style.css">
</head>

<body>

<div class="mainbox">
    <?php
        // Suppresses error warnings
        error_reporting(E_ERROR | E_PARSE);

        // Original: port 3307
            //add to DB
            $db = mysqli_connect("localhost", "root", "","dbresto","3307") or die("Unable to connect! ".mysqli_error());
            mysqli_select_db($db, "dbresto");

        $total = $_POST['totalPrice'];
        $name = $_POST['name'];
        $payment = $_POST['payment'];
        $discount = $_POST['totalDiscount'];
    ?>

    <h1>Checkout</h1>

    <div>
        <p><i>(Purchased items here)</i></p>
        <p style="color:red"><b>Amount Due: PHP <?php echo $_POST["totalPrice"]; ?></b></p>
        <p style="color:red"><b>Discount: PHP <?php echo $_POST["totalDiscount"]; ?></b></p>
    </div>

    <div class="blank"></div>

    <?php function checkOutForm() { ?>
    <div class="form"><form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <h2>Customer Information</h2>

        <label class="label" id="namelabel"><b>Name:</b></label>
        <input type="text" class="name" name="name" placeholder="Enter Name" size="100%" value="" required>
        
        <div class="field">
            <label class="label" id="addresslabel"><b>Address:</b></label>
            <input type="text" class="address" placeholder="Enter Address" size="100%">
        </div>
        
        <br>
        
        <div class="field">
            <h2>Pay this Amount:</h2>
            <div>
            <input type="number" class="payment" name="payment" required min="0" value="" placeholder="Enter Amount (PHP)">
            <img id="img" src="images/credit.png">
            </div>
        </div>

        <div class="button">
            <input type="submit" id="submit" class="btn" name="save"/>
        </div>

        <input type="hidden" name="totalPrice" value="<?php echo $_POST['totalPrice']; ?>">
        <input type="hidden" name="totalDiscount" value="<?php echo $_POST['totalDiscount']; ?>">
    </form></div>

    <?php
        }

        if(isset($payment, $total, $name)) {
            if($payment >= $total) {
                $insert = "INSERT INTO tblftrans(payment, total, name, date, totaldiscount) VALUES ('$payment', '$total', '$name', NOW(), '$discount')";
                mysqli_query($db, $insert);
                echo "<p>Transaction successful!</p>";

                // Receive change if payment is over amount due
                if($payment > $total) { echo "<b style='color:orange'>Change: PHP ".$payment - $total."</b>"; }
                else { echo "<b style='color:green'>Exact amount received.</b>"; }

                $paid = true;
            } else {
                checkOutForm();
                echo "<script>alert('Payment insufficient!');</script>";
            }
        } else {
            checkOutForm();
        }

        mysqli_close($db);
    ?>
    <br>
    <form action="main.php" method="post">
        <input type="submit" value="Return Home" class="homebtn">
    </form>
</div>

</body>

</html>