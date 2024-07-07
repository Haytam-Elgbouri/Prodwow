<?php
session_start();

if (!isset($_SESSION['userId']) || $_SESSION['userType'] != 'seller') {
    header('location:login.php');
    exit();
}

include('connexion.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include("header.php"); ?>
    <title>Add Product</title>
    <link rel="stylesheet" href="productdetails.css">
</head>
<body>
<?php include("navbar.php"); ?>
<div class="container addproduct">
<?php if (isset($_GET['error'])): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
            <?php endif; ?>
<div class="form">
    <h2>Add Product</h2>
    <form action="actionaddproduct.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="nom">Product Name:</label>
            <input type="text" id="nom" name="nom" required>
        </div>
        <div class="form-group">
            <label for="categorieId">Category:</label>
            <select id="categorieId" name="categorieId" required>
                <?php
                $result = $conn->query("SELECT idCategorie, nomCategorie FROM Categories");
                while ($row = $result->fetch_assoc()) {
                    echo '<option value="'.$row['idCategorie'].'">'.$row['nomCategorie'].'</option>';
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="prix">Price:</label>
            <input type="text" id="prix" name="prix" required>
        </div>
        <div class="form-group">
            <label for="description">Description:</label>
            <input type="text" id="description" name="description" required>
        </div>
        <div class="form-group">
            <label for="photo">Photo:</label>
            <input type="file" id="photo" name="photo" required>
        </div>
        <div class="buttonsection addprbtn">
            <input type="submit" value="Add Product">
        </div>
    </form>
            </div>
</div>
</body>
</html>
