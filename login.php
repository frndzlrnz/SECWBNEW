<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="login_style.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        .field {
            position: relative;
        }
        .toggle-password {
            position: absolute;
            right: 20%;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="login"><center>
        <h1>Login</h1>
        <form method="post" action="admin.php">
            <label><b>Username</b></label>
            <input type="text" name="username" placeholder="Enter Username" size="100%" required>

            <div class="field">
                <label><b>Password</b></label>
                <input type="password" name="password" id="password" class="pswd" placeholder="Enter Password" size="100%" required>
                <span class="toggle-password" onclick="togglePasswordVisibility('password')">👁️</span>
            </div>

            <div class="g-recaptcha" data-sitekey="6LdpOPMpAAAAAJ_QkvPEDLdd32FyZMknQMsnj_E8"></div>
            <p><input type="submit" class="btn" value="Login" name="submitbtn"></p>
        </form>
        <a href="welcome.php"><button class="btn">Back</button></a><br><br>
        <a href="registration.php"><button class="btn">Register</button></a><br><br>
    </center></div>

    <script>
        function togglePasswordVisibility(id) {
            var passwordField = document.getElementById(id);
            var toggleIcon = passwordField.nextElementSibling;
            if (passwordField.type === "password") {
                passwordField.type = "text";
                toggleIcon.textContent = "🙈"; // Change icon to hide eyes
            } else {
                passwordField.type = "password";
                toggleIcon.textContent = "👁️"; // Change icon to show eyes
            }
        }
    </script>
</body>
</html>