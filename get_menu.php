<?php
// Establish a database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "dbresto";

            //add to DB
            $conn = mysqli_connect("localhost", "root", "","dbresto","3307") or die("Unable to connect! ".mysqli_error());
            mysqli_select_db($conn, "dbresto");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch data from the tblfood table
$query = "SELECT * FROM tblfood";
$result = mysqli_query($conn, $query);

$data = array();
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

mysqli_close($conn);

// Return the data as JSON
echo json_encode($data);
?>
