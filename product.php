<!DOCTYPE html>
<html lang="en">
<?php session_start(); ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product</title>
    <?php include("header.php");?>
</head>
<?php
include('connexion.php');

$stmt = $conn->prepare("SELECT p.idProduit, p.nom, p.prix, p.photo, c.nomCategorie, s.nom as sellerNom, s.prenom as sellerPrenom 
                        FROM Products p 
                        JOIN Categories c ON p.categorieId = c.idCategorie
                        JOIN Sellers s ON p.idSeller = s.idSeller");
$stmt->execute();
$stmt->bind_result($idProduit, $nomProduit, $prix, $photo, $nomCategorie, $sellerNom, $sellerPrenom);
include("navbar.php"); ?>
    <div class="homecontainer">
        <h2>Products</h2>
        <div class="product-grid">
            <?php while ($stmt->fetch()): ?>
                <div class="product-card">
                    <div class="product-info">
                        <p><?php echo htmlspecialchars($nomCategorie); ?></p>
                        <p>$<?php echo htmlspecialchars($prix); ?></p>

                    </div>
                    <img src="<?php echo htmlspecialchars($photo); ?>" alt="<?php echo htmlspecialchars($nomProduit); ?>">
                    <h4><?php echo htmlspecialchars($nomProduit); ?></h4>
                    <a href="productdetails.php?idProduit=<?php echo $idProduit; ?>"><button>SEE MORE</button></a>
                    </div>
            <?php endwhile; ?>
        </div>
    </div>
<?php include("footer.php"); ?>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>