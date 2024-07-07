
<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include("header.php"); ?>
    <title>ElectroBuy Login</title>
</head>
<body>
    <?php include("navbar.php"); ?>
    <div class="container">
    <?php if (isset($_GET['error'])): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>
        <div class="form">
            <h2>Login</h2>
            <form action="actionlogin.php" method="POST">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="buttonsection">
                    <input type="submit" value="Login">
                    <input type="reset" value="Clear">
                </div>
            </form>
        </div>
    </div>
    <button class="btn-secondary switch-btn"><a class="" href="registration.php">New account?</a></button>
    <?php include("footer.php"); ?>
</body>
</html>
