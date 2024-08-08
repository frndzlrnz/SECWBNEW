<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="login_style.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
    <div class="login"><center>
        <h1>Login</h1>
        <form method="post" action="admin.php">
            <label><b>Username</b></label>
            <input type="text" name="username" placeholder="Enter Username" size="100%" required>

            <div class="field">
                <label><b>Password</b></label>
                <input type="password" name="password" class="pswd" placeholder="Enter Password" size="100%" required>
            </div>

            <div class="g-recaptcha" data-sitekey="6LdpOPMpAAAAAJ_QkvPEDLdd32FyZMknQMsnj_E8"></div>
            <p><input type="submit" class="btn" value="Login" name="submitbtn"></p>
        </form><br>
        <a href="welcome.php"><button class="btn">Back</button></a><br><br>
    </center></div>
</body>
</html>
