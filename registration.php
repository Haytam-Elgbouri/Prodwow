<?php
include("header.php");
?>
<title>ElectroBuy Registration</title>
</head>
<body>
<?php
include("navbar.php");
?>

<?php
$nom = isset($_GET['nom']) ? htmlspecialchars($_GET['nom']) : '';
$prenom = isset($_GET['prenom']) ? htmlspecialchars($_GET['prenom']) : '';
$email = isset($_GET['email']) ? htmlspecialchars($_GET['email']) : '';
$city = isset($_GET['city']) ? htmlspecialchars($_GET['city']) : '';
?>

<div class="container">
    <div class="form">
        <h2>Register</h2>
        <?php
        if (isset($_GET['err'])) {
            switch ($_GET['err']) {
                case 1:
                    echo "<p style='color: red;'>Passwords do not match.</p>";
                    break;
                case 2:
                    echo "<p style='color: red;'>Email already exists.</p>";
                    break;
                case 3:
                    echo "<p style='color: red;'>Registration failed. Please try again.</p>";
                    break;
            }
        }
        ?>
        <form action="actionregistration.php" method="POST">
            <div class="form-group">
                <label for="nom">Nom:</label>
                <input type="text" id="nom" name="nom" value="<?php echo $nom; ?>" required>
            </div>
            <div class="form-group">
                <label for="prenom">Prenom:</label>
                <input type="text" id="prenom" name="prenom" value="<?php echo $prenom; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>
            </div>
            <div class="form-group">
                <label for="city">City:</label>
                <input type="text" id="city" name="city" value="<?php echo $city; ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="Cpassword">Confirm Password:</label>
                <input type="password" id="Cpassword" name="Cpassword" required>
            </div>
            <div class="buttonsection">
                <input type="submit" value="Register">
                <input type="reset" value="Clear">
            </div>
        </form>
    </div>
</div>
<button class="btn-secondary switch-btn"><a href="login.php">Already have an account? Sign In</a></button>

<?php
include("footer.php");
?>
</body>
</html>
