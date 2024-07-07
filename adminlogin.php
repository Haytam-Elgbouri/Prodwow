<!DOCTYPE html>
<html lang="en">
<head>
    <?php include("header.php"); ?>
    <title>Admin Login</title>
</head>
<body>
    <?php include("navbar.php"); ?>
    <div class="container">
    <div class="form">
        <h2>Admin Login</h2>
        <form action="actionadminlogin.php" method="POST">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="buttonsection .addprbtn">
                <input type="submit" value="Login">
            </div>
        </form>
    </div>
</div>
    <?php include("footer.php"); ?>
</body>
</html>
