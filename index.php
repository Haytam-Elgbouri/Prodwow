<?php
session_start();
    include("header.php");
?>
    <title>PROWOW - Home</title>
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
<body>
<?php
include("navbar.php");
?>

    <div class="hero-section">
        <video autoplay loop muted plays-inline class="bg_clip">
            <source src="img/Comp 1.mp4" type="video/mp4">
        </video>
    </div>
    <div class="homecontainer">
        <h2>Featured Products</h2>
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
<?php
    include("footer.php");
?>
</body>
</html>
