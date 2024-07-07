<?php
session_start();
include('connexion.php');

if (!isset($_SESSION['userId']) || !isset($_SESSION['userType'])) {
    header('location:login.php');
    exit();
}

$userId = $_SESSION['userId'];
$userType = $_SESSION['userType'];

if ($userType == 'client') {
    $stmt = $conn->prepare('SELECT nom, prenom, email, city FROM Clients WHERE idClient = ?');
} else if ($userType == 'seller') {
    $stmt = $conn->prepare('SELECT nom, prenom, phone, email, adresse, city FROM Sellers WHERE idSeller = ?');
} else {
    header('location:login.php');
    exit();
}

$stmt->bind_param('i', $userId);
$stmt->execute();
if ($userType == 'client') {
    $stmt->bind_result($nom, $prenom, $email, $city);
} else {
    $stmt->bind_result($nom, $prenom, $phone, $email, $adresse, $city);
}
$stmt->fetch();
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_nom = $_POST['nom'];
    $new_prenom = $_POST['prenom'];
    $new_email = $_POST['email'];
    $new_city = $_POST['city'];
    $new_password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

    if ($userType == 'client') {
        if ($new_password) {
            $stmt = $conn->prepare('UPDATE Clients SET nom = ?, prenom = ?, email = ?, city = ?, password = ? WHERE idClient = ?');
            $stmt->bind_param('sssssi', $new_nom, $new_prenom, $new_email, $new_city, $new_password, $userId);
        } else {
            $stmt = $conn->prepare('UPDATE Clients SET nom = ?, prenom = ?, email = ?, city = ? WHERE idClient = ?');
            $stmt->bind_param('ssssi', $new_nom, $new_prenom, $new_email, $new_city, $userId);
        }
    } else {
        $new_phone = $_POST['phone'];
        $new_adresse = $_POST['adresse'];
        if ($new_password) {
            $stmt = $conn->prepare('UPDATE Sellers SET nom = ?, prenom = ?, phone = ?, email = ?, adresse = ?, city = ?, password = ? WHERE idSeller = ?');
            $stmt->bind_param('ssissssi', $new_nom, $new_prenom, $new_phone, $new_email, $new_adresse, $new_city, $new_password, $userId);
        } else {
            $stmt = $conn->prepare('UPDATE Sellers SET nom = ?, prenom = ?, phone = ?, email = ?, adresse = ?, city = ? WHERE idSeller = ?');
            $stmt->bind_param('ssisssi', $new_nom, $new_prenom, $new_phone, $new_email, $new_adresse, $new_city, $userId);
        }
    }

    if ($stmt->execute()) {
        echo "Profile updated successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$sellerProducts = [];
if ($userType == 'seller') {
    $stmt = $conn->prepare('SELECT idProduit, nom, prix, photo, categorieId FROM Products WHERE idSeller = ?');
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $stmt->bind_result($idProduit, $nomProduit, $prix, $photo, $categorieId);
    while ($stmt->fetch()) {
        $sellerProducts[] = [
            'idProduit' => $idProduit,
            'nomProduit' => $nomProduit,
            'prix' => $prix,
            'photo' => $photo,
            'categorieId' => $categorieId
        ];
    }
    $stmt->close();
}

$cartItems = [];
if ($userType == 'client') {
    $stmt = $conn->prepare('
        SELECT p.idProduit, p.nom, p.prix, p.photo 
        FROM Cart c 
        JOIN Products p ON c.idProduit = p.idProduit 
        WHERE c.idClient = ?
    ');
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $stmt->bind_result($idProduit, $nomProduit, $prix, $photo);
    while ($stmt->fetch()) {
        $cartItems[] = [
            'idProduit' => $idProduit,
            'nomProduit' => $nomProduit,
            'prix' => $prix,
            'photo' => $photo
        ];
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include("header.php"); ?>
    <title>My Account</title>
    <link rel="stylesheet" href="productdetails.css">
</head>
<body>
<?php include("navbar.php"); ?>
<div class="container">
    <div class="form">
        <h2>My Account</h2>
        <form action="myaccount.php" method="POST" id="accountForm">
            <div class="form-group">
                <label for="nom">Nom:</label>
                <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($nom); ?>" class="locked" required>
            </div>
            <div class="form-group">
                <label for="prenom">Prenom:</label>
                <input type="text" id="prenom" name="prenom" value="<?php echo htmlspecialchars($prenom); ?>" class="locked" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" class="locked" required>
            </div>
            <?php if ($userType == 'seller'): ?>
                <div class="form-group">
                    <label for="phone">Phone:</label>
                    <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>" class="locked" required>
                </div>
                <div class="form-group">
                    <label for="adresse">Adresse:</label>
                    <input type="text" id="adresse" name="adresse" value="<?php echo htmlspecialchars($adresse); ?>" class="locked" required>
                </div>
            <?php endif; ?>
            <div class="form-group">
                <label for="city">City:</label>
                <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($city); ?>" class="locked" required>
            </div>
            <div class="form-group">
                <label for="password">New Password:</label>
                <input type="password" id="password" name="password" class="locked">
            </div>
            <div class="buttonsection">
                <input type="submit" value="Update" id="updateButton" class="locked">
                <button class="lockbtn" id="editButton" type="button">Edit</button>
            </div>
        </form>
    </div>

    <?php if ($userType == 'client'): ?>
        <div class="ad">
            <h3>Hey <?php echo htmlspecialchars($nom); ?>, want to become a seller?</h3>
            <a href="become_seller.php">Become a Seller</a>
        </div>
        <div class="cart">
            <h3>My Cart</h3>
            <div class="product-grid">
                <?php foreach ($cartItems as $item): ?>
                    <div class="product-card">
                        <div class="product-info">
                            <p><?php echo htmlspecialchars($item['prix']); ?>DH</p>
                        </div>
                        <img src="<?php echo htmlspecialchars($item['photo']); ?>" alt="<?php echo htmlspecialchars($item['nomProduit']); ?>">
                        <h4><?php echo htmlspecialchars($item['nomProduit']); ?></h4>
                        <a href="productdetails.php?idProduit=<?php echo $item['idProduit']; ?>"><button>SEE MORE</button></a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php else: ?>
        <div class="ad">
            <h3>Want to add a product?</h3>
            <a href="addproduct.php">Add Product</a>
        </div>
        <div class="products">
            <h3>My Products</h3>
            <div class="product-grid">
                <?php foreach ($sellerProducts as $product): ?>
                    <div class="product-card">
                        <div class="product-info">
                            <p>Category ID: <?php echo htmlspecialchars($product['categorieId']); ?></p>
                            <p><?php echo htmlspecialchars($product['prix']); ?>DH</p>
                        </div>
                        <img src="<?php echo htmlspecialchars($product['photo']); ?>" alt="<?php echo htmlspecialchars($product['nomProduit']); ?>">
                        <h4><?php echo htmlspecialchars($product['nomProduit']); ?></h4>
                        <a href="updateproduct.php?idProduit=<?php echo $product['idProduit']; ?>"><button>UPDATE</button></a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include("footer.php"); ?>
<script>
    document.getElementById('editButton').addEventListener('click', function() {
        var elements = document.querySelectorAll('#accountForm .locked');
        elements.forEach(function(element) {
            element.classList.remove('locked');
            element.removeAttribute('disabled');
        });
    });
</script>
</body>
</html>
