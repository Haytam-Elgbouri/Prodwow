<?php
session_start();
include('connexion.php');

if (!isset($_SESSION['userId']) || $_SESSION['userType'] != 'seller') {
    header('location:login.php');
    exit();
}

if (!isset($_GET['idProduit'])) {
    header('location:myaccount.php');
    exit();
}

$idSeller = $_SESSION['userId'];
$idProduit = $_GET['idProduit'];

$stmt = $conn->prepare('SELECT nom, description, prix, photo, categorieId FROM Products WHERE idProduit = ? AND idSeller = ?');
$stmt->bind_param('ii', $idProduit, $idSeller);
$stmt->execute();
$stmt->bind_result($nomProduit, $description, $prix, $photo, $categorieId);
$stmt->fetch();
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_nomProduit = $_POST['nom'];
    $new_description = $_POST['description'];
    $new_prix = $_POST['prix'];
    $new_categorieId = $_POST['categorieId'];
    
    if (!empty($_FILES['photo']['name'])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["photo"]["name"]);
        move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file);
        $new_photo = $target_file;

        $stmt = $conn->prepare('UPDATE Products SET nom = ?, description = ?, prix = ?, photo = ?, categorieId = ? WHERE idProduit = ? AND idSeller = ?');
        $stmt->bind_param('ssdsdii', $new_nomProduit, $new_description, $new_prix, $new_photo, $new_categorieId, $idProduit, $idSeller);
    } else {
        $stmt = $conn->prepare('UPDATE Products SET nom = ?, description = ?, prix = ?, categorieId = ? WHERE idProduit = ? AND idSeller = ?');
        $stmt->bind_param('ssdiii', $new_nomProduit, $new_description, $new_prix, $new_categorieId, $idProduit, $idSeller);
    }

    if ($stmt->execute()) {
        echo "Product updated successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    header('location:myaccount.php');
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include("header.php"); ?>
    <title>Update Product</title>
    <link rel="stylesheet" href="productdetails.css">
</head>
<body>
<?php include("navbar.php"); ?>
<div class="container">
    <div class="form">
        <h2>Update Product</h2>
        <form action="updateproduct.php?idProduit=<?php echo $idProduit; ?>" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nom">Product Name:</label>
                <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($nomProduit); ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" required><?php echo htmlspecialchars($description); ?></textarea>
            </div>
            <div class="form-group">
                <label for="prix">Price:</label>
                <input type="text" id="prix" name="prix" value="<?php echo htmlspecialchars($prix); ?>" required>
            </div>
            <div class="form-group">
                <label for="categorieId">Category ID:</label>
                <input type="text" id="categorieId" name="categorieId" value="<?php echo htmlspecialchars($categorieId); ?>" required>
            </div>
            <div class="form-group">
                <label for="photo">Photo:</label>
                <input type="file" id="photo" name="photo">
                <?php if (!empty($photo)): ?>
                    <img src="<?php echo htmlspecialchars($photo); ?>" alt="Product Image" width="100">
                <?php endif; ?>
            </div>
            <div class="buttonsection">
                <input type="submit" value="Update">
            </div>
        </form>
    </div>
</div>
<?php include("footer.php"); ?>
</body>
</html>
