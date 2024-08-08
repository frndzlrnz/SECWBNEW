<?php

// Displays an error window
function errorWindow($errText, $buttonText) {
    echo "<center>";
    echo "<h1>ERROR</h1><h2 style='color:red'>$errText</h2>";
    echo "<a href='login.php'><button class='btn'>$buttonText</button></a><br><br>";
    echo "</center>";
    die();
}

?>