<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>ITPROG Ordering System</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="">
    

    <div class="container">
        <header>
            <h1>Online Ordering</h1>
           
            <div class="shopping">
                <img src="images/shopping.svg">
                <span class="quantity">0</span>
                <a href="logout.php"><img src="images/logout.png" class="logout-icon"></a>
            </div>
        </header>

        <div class="list">
          
        </div>
    </div>

    <div class="card"><form method="post" action="checkout.php">
        <h1>Food Cart</h1>

        <ul class="listCard">
        </ul>

        <div class="checkOut">
            <div class="total">0</div>
            <div class="closeShopping">Close</div>
        </div>

        <!-- Redirect to checkout.php -->
        <button type="submit" class="payment">Make Payment Here</button>
        <input type="hidden" id="totalPrice" name="totalPrice">
        <input type="hidden" id="totalDiscount" name="totalDiscount">
    </form></div>

    <script src="app.js"></script>
</body>

</html>