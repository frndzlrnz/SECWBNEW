<?php
session_start();

// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dbresto";
$port = "3307";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullName = $_POST['fullName'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $profilePhoto = $_FILES['profilePhoto'];
    $role = 'user'; // Default role
    $error = '';
    // Magic numbers for JPEG and PNG
    $jpegMagicNumber = "\xFF\xD8\xFF\xE0";
    $pngMagicNumber = "\x89\x50\x4E\x47\x0D\x0A\x1A\x0A";

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format.';
    } 
    elseif (!preg_match("/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/", $email)) {
      $error = 'Invalid email format.';
    }
    // Validate phone number (09XX XXX XXXX or +639XX XXX XXXX)
    elseif (!preg_match("/^(09|\+639)\d{9}$/", $phone)) {
        $error = 'Invalid phone number format. Use format: 09XXXXXXXXX or +639XXXXXXXXX.';
    }
    // Validate password
    elseif ($password !== $confirmPassword) {
        $error = 'Passwords do not match.';
    } 
    elseif (!preg_match("/^[a-zA-Z ]+$/", $username)) {
      $error = 'Username should contain only characters';
    }
    elseif (!preg_match("#[0-9]+#", $password)) {
      $error = 'Password should contain atleast 1 digit';
    }
    elseif (!preg_match("#[a-z]+#", $password)) {
      $error = 'Password should contain atleast 1 lowercase char';
    }
    elseif (!preg_match("#[A-Z]+#", $password)) {
      $error = 'Password should contain atleast 1 uppercase char';
    }
    elseif (strlen($password) < 8) {
      $error = 'Password should atleast be 8 digits';
    }
    else {
        // Generate a random salt
        $salt = bin2hex(random_bytes(5));
        // Combine the salt with the password
        $saltedPassword = $password . $salt;
        // Hash the combined salted password
        $hashedPassword = password_hash($saltedPassword, PASSWORD_BCRYPT);

        // Validate and upload profile photo
        $allowedMimeTypes = ['image/jpeg', 'image/png'];
        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        $detectedMimeType = mime_content_type($profilePhoto['tmp_name']);
        $fileExtension = strtolower(pathinfo($profilePhoto['name'], PATHINFO_EXTENSION));

        if (in_array($detectedMimeType, $allowedMimeTypes) && in_array($fileExtension, $allowedExtensions)) {
            if ($profilePhoto['error'] == UPLOAD_ERR_OK && is_uploaded_file($profilePhoto['tmp_name'])) {
                $fileHandle = fopen($profilePhoto['tmp_name'], 'rb');
                $fileHeader = fread($fileHandle, 8);
                fclose($fileHandle);
                if (substr($fileHeader, 0, 4) === $jpegMagicNumber) {
                  $fileType = 'jpeg';
                } elseif (substr($fileHeader, 0, 8) === $pngMagicNumber) { // Check for PNG magic number
                  $fileType = 'png';
                } else {
                  $error = 'Invalid image file format.';
                  // Handle error
                }
                
                $photoContent = addslashes(file_get_contents($profilePhoto['tmp_name']));
                $sql = "INSERT INTO users (fullName, username, email, phone, password, salt, profilePhoto, role) VALUES ('$fullName', '$username', '$email', '$phone', '$hashedPassword', '$salt', '$photoContent', '$role')";

if ($conn->query($sql) === TRUE) {
    echo "<script>
            if (confirm('Are you sure that you\\'ve put down all the correct information?')) {
                window.location.href = 'login.php';
            } else {
                window.location.href = 'registration.php'; // Redirect back to registration page
            }
          </script>";
    exit;
} else {
    $error = 'Failed to save user data: ' . $conn->error;
                }
            } else {
                $error = 'Profile photo upload error.';
            }
        } else {
            $error = 'Invalid file type. Only JPG, PNG files are allowed.';
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registration</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
    }
    .container {
      max-width: 400px;
      margin: 50px auto;
      padding: 20px;
      background: #fff;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    h2 {
      text-align: center;
      margin-bottom: 20px;
    }
    .form-group {
      margin-bottom: 20px;
    }
    label {
      display: block;
      font-weight: bold;
    }
    input[type="text"],
    input[type="email"],
    input[type="tel"],
    input[type="password"],
    input[type="file"] {
      width: 90%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    input[type="submit"] {
      width: 100%;
      padding: 10px;
      background-color: #007bff;
      border: none;
      border-radius: 5px;
      color: #fff;
      cursor: pointer;
    }
    input[type="submit"]:hover {
      background-color: #0056b3;
    }
    .error {
      color: red;
      text-align: center;
      margin-bottom: 20px;
    }
  </style>
  <script>
    function confirmSubmission() {
      return confirm('Are you sure that you\\'ve put down all the correct information?');
    }
  </script>
</head>
<body>
  <div class="container">
    <h2>Registration</h2>
    <?php if (isset($error)) { ?>
      <p class="error"><?php echo $error; ?></p>
    <?php } ?>
    <form id="registrationForm" method="POST" action="registration.php" enctype="multipart/form-data" onsubmit="return confirmSubmission()">
      <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
      </div>
      <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
      </div>
      <div class="form-group">
        <label for="confirmPassword">Confirm Password:</label>
        <input type="password" id="confirmPassword" name="confirmPassword" required>
      </div>
      <div class="form-group">
        <label for="fullName">Full Name:</label>
        <input type="text" id="fullName" name="fullName" required>
      </div>
      <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
      </div>
      <div class="form-group">
        <label for="phone">Phone Number:</label>
        <input type="tel" id="phone" name="phone" pattern="^(09|\+639)\d{9}$" required>
        <small>Format: 09XXXXXXXXX or +639674233057</small>
      </div>
      <div class="form-group">
        <label for="profilePhoto">Profile Photo:</label>
        <input type="file" id="profilePhoto" name="profilePhoto" accept=".jpg, .jpeg" required>
      </div>
      <input type="submit" value="Register">
    </form>
  </div>
</body>
</html>
