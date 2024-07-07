<?php
session_start();
if (!isset($_SESSION['userId'])) {
    header('location:login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<?php include("header.php"); ?>
    <title>Become a Seller</title>
</head>
<body>
<?php include("navbar.php"); ?>
<div class="container">
    <h2>Become a Seller</h2>
    <form action="action_become_seller.php" method="POST">
        <div class="form-group">
            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" required>
        </div>
        <div class="form-group">
            <label for="adresse">Adresse:</label>
            <input type="text" id="adresse" name="adresse" required>
        </div>
        <div class="buttonsection">
            <input type="submit" value="Submit">
        </div>
    </form>
</div>
<?php include("footer.php"); ?>
</body>
</html>
