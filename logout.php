<html>
<head>
    <title>Log Out</title>
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
        } else {
            session_destroy();
            echo "<center>";
            echo "<h1>Logged Out</h2>";
            echo "<h2>Session ended. You will be redirected shortly.</h2>";
            echo "</center>";
            header("refresh:4; url=login.php");
        }
    ?>
</div>

</body>
</html>