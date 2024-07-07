<?php
session_start();
include('connexion.php');

$query = isset($_GET['query']) ? $_GET['query'] : '';

if (empty($query)) {
    header('location:index.php');
    exit();
}

$query = "%" . $query . "%";

$stmt = $conn->prepare("
    SELECT p.idProduit, p.nom, p.prix, p.photo, c.nomCategorie, s.nom as sellerNom, s.prenom as sellerPrenom
    FROM Products p
    JOIN Categories c ON p.categorieId = c.idCategorie
    JOIN Sellers s ON p.idSeller = s.idSeller
    WHERE p.nom LIKE ? OR c.nomCategorie LIKE ? OR p.description LIKE ?");
$stmt->bind_param("sss", $query, $query, $query);
$stmt->execute();
$stmt->bind_result($idProduit, $nomProduit, $prix, $photo, $nomCategorie, $sellerNom, $sellerPrenom);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include("header.php"); ?>
    <title>Search Results</title>
</head>
<body>
<?php include("navbar.php"); ?>
    <div class="searchsession">
    <h2>Search Results for "<?php echo htmlspecialchars($_GET['query']); ?>"</h2>
    <div class="product-grid">
            <?php while ($stmt->fetch()): ?>
                <div class="product-card">
                    <div class="product-info">
                        <p><?php echo htmlspecialchars($nomCategorie); ?></p>
                        <p><?php echo htmlspecialchars($prix); ?>DH</p>

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