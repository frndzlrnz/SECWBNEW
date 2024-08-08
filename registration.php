<?php
session_start();

// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dbresto";
$port = "3307";

// Log file path
$logFile = 'logs/registration.log';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);
// Check connection
if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error, 3, $logFile);
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
        error_log($error, 3, $logFile);
    } elseif (!preg_match("/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/", $email)) {
        $error = 'Invalid email format.';
        error_log($error, 3, $logFile);
    }
    // Validate phone number
    elseif (!preg_match("/^(09|\+639)\d{9}$/", $phone)) {
        $error = 'Invalid phone number format. Use format: 09XXXXXXXXX or +639XXXXXXXXX.';
        error_log($error, 3, $logFile);
    }
    // Validate password
    elseif ($password !== $confirmPassword) {
        $error = 'Passwords do not match.';
        error_log($error, 3, $logFile);
    } elseif (!preg_match("/^[a-zA-Z ]+$/", $username)) {
        $error = 'Username should contain only characters';
        error_log($error, 3, $logFile);
    } elseif (!preg_match("#[0-9]+#", $password)) {
        $error = 'Password should contain at least 1 digit';
        error_log($error, 3, $logFile);
    } elseif (!preg_match("#[a-z]+#", $password)) {
        $error = 'Password should contain at least 1 lowercase char';
        error_log($error, 3, $logFile);
    } elseif (!preg_match("#[A-Z]+#", $password)) {
        $error = 'Password should contain at least 1 uppercase char';
        error_log($error, 3, $logFile);
    } elseif (strlen($password) < 8) {
        $error = 'Password should be at least 8 characters';
        error_log($error, 3, $logFile);
    } else {

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

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
                    error_log($error, 3, $logFile);
                }
                
                $photoContent = addslashes(file_get_contents($profilePhoto['tmp_name']));
                $sql = "INSERT INTO users (fullName, username, email, phone, password,profilePhoto, role) VALUES ('$fullName', '$username', '$email', '$phone', '$hashedPassword', '$photoContent', '$role')";

                if ($conn->query($sql) === TRUE) {
                    error_log("User registered successfully: $email", 3, $logFile);
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
                    error_log($error, 3, $logFile);
                }
            } else {
                $error = 'Profile photo upload error.';
                error_log($error, 3, $logFile);
            }
        } else {
            $error = 'Invalid file type. Only JPG, PNG files are allowed.';
            error_log($error, 3, $logFile);
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
      position: relative; /* For positioning the eye icon */
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
    .toggle-password {
      position: absolute;
      right: 5%;
      top: 25px;
      cursor: pointer;
      font-size: 18px;
    }
    .strength-meter {
      height: 5px;
      margin-top: 5px;
      background-color: #ccc;
      border-radius: 3px;
    }
    .strength-meter div {
      height: 100%;
      width: 0;
      border-radius: 3px;
      transition: width 0.3s;
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
    #password-requirements {
    list-style-type: none;
    padding: 0;
    margin: 10px 0 0 0;
    font-size: 0.9em;
    color: #ff0000; /* Red for invalid requirements */
    }

  #password-requirements .valid {
    color: #28a745; /* Green for valid requirements */
    }

  </style>
  <script>
    // Function to toggle password visibility
    function togglePasswordVisibility(id) {
      var passwordField = document.getElementById(id);
      var toggleIcon = passwordField.nextElementSibling;
      if (passwordField.type === "password") {
        passwordField.type = "text";
        toggleIcon.textContent = "🙈"; // Hide eyes
      } else {
        passwordField.type = "password";
        toggleIcon.textContent = "👁️"; // Show eyes
      }
    }

// Function to update password strength and requirement validity
function updatePasswordStrength(password) {
  var strengthBar = document.getElementById('strength-bar');
  var strength = 0;

  // Requirement elements
  var lengthRequirement = document.getElementById('length');
  var uppercaseRequirement = document.getElementById('uppercase');
  var lowercaseRequirement = document.getElementById('lowercase');
  var numberRequirement = document.getElementById('number');
  var specialRequirement = document.getElementById('special');

  // Check password length
  if (password.length >= 8) {
    strength++;
    lengthRequirement.classList.remove('invalid');
    lengthRequirement.classList.add('valid');
  } else {
    lengthRequirement.classList.remove('valid');
    lengthRequirement.classList.add('invalid');
  }

  // Check for uppercase characters
  if (/[A-Z]/.test(password)) {
    strength++;
    uppercaseRequirement.classList.remove('invalid');
    uppercaseRequirement.classList.add('valid');
  } else {
    uppercaseRequirement.classList.remove('valid');
    uppercaseRequirement.classList.add('invalid');
  }

  // Check for lowercase characters
  if (/[a-z]/.test(password)) {
    strength++;
    lowercaseRequirement.classList.remove('invalid');
    lowercaseRequirement.classList.add('valid');
  } else {
    lowercaseRequirement.classList.remove('valid');
    lowercaseRequirement.classList.add('invalid');
  }

  // Check for digits
  if (/[0-9]/.test(password)) {
    strength++;
    numberRequirement.classList.remove('invalid');
    numberRequirement.classList.add('valid');
  } else {
    numberRequirement.classList.remove('valid');
    numberRequirement.classList.add('invalid');
  }

  // Check for special characters
  if (/[\W]/.test(password)) {
    strength++;
    specialRequirement.classList.remove('invalid');
    specialRequirement.classList.add('valid');
  } else {
    specialRequirement.classList.remove('valid');
    specialRequirement.classList.add('invalid');
  }

  var strengthColors = ["#ccc", "red", "orange", "yellow", "green"];
  strengthBar.style.width = (strength * 20) + "%";
  strengthBar.style.backgroundColor = strengthColors[strength];
}


    // Event listener to update password strength as the user types
    document.addEventListener('DOMContentLoaded', function () {
      var passwordField = document.getElementById('password');
      passwordField.addEventListener('input', function () {
        updatePasswordStrength(this.value);
      });
    });
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
        <span class="toggle-password" onclick="togglePasswordVisibility('password')">👁️</span>
        <div class="strength-meter">
          <div id="strength-bar"></div>
        </div>
        <ul id="password-requirements">
          <li id="length" class="invalid">At least 8 characters</li>
          <li id="uppercase" class="invalid">At least 1 uppercase letter</li>
          <li id="lowercase" class="invalid">At least 1 lowercase letter</li>
          <li id="number" class="invalid">At least 1 digit</li>
          <li id="special" class="invalid">At least 1 special character</li>
        </ul>
      </div>
      <div class="form-group">
        <label for="confirmPassword">Confirm Password:</label>
        <input type="password" id="confirmPassword" name="confirmPassword" required>
        <span class="toggle-password" onclick="togglePasswordVisibility('confirmPassword')">👁️</span>
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