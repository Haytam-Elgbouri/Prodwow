<?php
session_start();
include('connexion.php');

if (!isset($_GET['idProduit'])) {
    header('location:products.php');
    exit();
}

$idProduit = $_GET['idProduit'];

// Fetch product details
$sql = "
    SELECT p.nom, p.description, p.prix, p.photo, c.nomCategorie, s.nom as sellerNom, s.prenom as sellerPrenom 
    FROM Products p 
    JOIN Categories c ON p.categorieId = c.idCategorie 
    JOIN Sellers s ON p.idSeller = s.idSeller 
    WHERE p.idProduit = ?
";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
}

$stmt->bind_param('i', $idProduit);
$stmt->execute();
$stmt->bind_result($nomProduit, $description, $prix, $photo, $nomCategorie, $sellerNom, $sellerPrenom);
$stmt->fetch();
$stmt->close();

// Fetch comments and ratings
$comments = [];
$sql = "
    SELECT co.comment, r.rating, cl.nom, cl.prenom 
    FROM Comments co 
    JOIN Clients cl ON co.idClient = cl.idClient 
    LEFT JOIN Ratings r ON co.idProduit = r.idProduit AND co.idClient = r.idClient
    WHERE co.idProduit = ?
";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
}

$stmt->bind_param('i', $idProduit);
$stmt->execute();
$stmt->bind_result($comment, $rating, $clientNom, $clientPrenom);

while ($stmt->fetch()) {
    $comments[] = ['comment' => $comment, 'rating' => $rating, 'clientNom' => $clientNom, 'clientPrenom' => $clientPrenom];
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include("header.php"); ?>
    <title>Product Details</title>
    <link rel="stylesheet" href="productdetails.css">
</head>
<body>
<?php include("navbar.php"); ?>
<div class="container">
    <div class="productdetail">
        <img src="<?php echo htmlspecialchars($photo); ?>" alt="Product Image">
        <div class="productinfo">
            <h2><?php echo htmlspecialchars($nomProduit); ?></h2>
            <p>Category: <?php echo htmlspecialchars($nomCategorie); ?></p>
            <p>Price: <?php echo htmlspecialchars($prix); ?>DH</p>
            <p>Description: <?php echo htmlspecialchars($description); ?></p>
            <p>Seller: <?php echo htmlspecialchars($sellerNom . ' ' . $sellerPrenom); ?></p>
            <?php if (isset($_SESSION['userId']) && $_SESSION['userType'] == 'client'): ?>
                <form action="add_to_cart.php" method="POST">
                    <input type="hidden" name="idProduit" value="<?php echo $idProduit; ?>">
                    <button type="submit">Add to Cart</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
    <div class="commentRating">
        <div class="reviews">
            <h3>Comments and Ratings</h3>
            <?php foreach ($comments as $comment): ?>
                <div class="comment">
                    <p><strong><?php echo htmlspecialchars($comment['clientNom'] . ' ' . $comment['clientPrenom']); ?></strong> rated: <?php echo htmlspecialchars($comment['rating'] ?: 'No rating'); ?>/5</p>
                    <p><?php echo htmlspecialchars($comment['comment']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="writecomment">
            <?php if (isset($_SESSION['userId'])): ?>
                <h3>Leave a Comment and Rating</h3>
                <form action="actioncomment.php" method="POST">
                    <input type="hidden" name="idProduit" value="<?php echo $idProduit; ?>">
                    <div class="form-group">
                        <label for="comment">Comment:</label>
                        <textarea id="comment" name="comment" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="rating">Rating:</label>
                        <select id="rating" name="rating" required>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>
                    <div class="buttonsection">
                        <input type="submit" value="Submit">
                    </div>
                </form>
            <?php else: ?>
                <p>Please <a href="login.php">login</a> to leave a comment and rating.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php include("footer.php"); ?>
</body>
</html>

<?php
$conn->close();
?>
