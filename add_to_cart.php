<?php
session_start();
include('connexion.php');

if (!isset($_SESSION['userId']) || $_SESSION['userType'] != 'client') {
    header('location:login.php');
    exit();
}

if (!isset($_POST['idProduit'])) {
    header('location:products.php');
    exit();
}

$idClient = $_SESSION['userId'];
$idProduit = $_POST['idProduit'];

$stmt = $conn->prepare('INSERT INTO Cart (idClient, idProduit) VALUES (?, ?)');
$stmt->bind_param('ii', $idClient, $idProduit);

if ($stmt->execute()) {
    header('location:myaccount.php');
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
