<?php
session_start();
include('connexion.php');

if (!isset($_SESSION['userId']) || $_SESSION['userType'] != 'client') {
    header('location:login.php');
    exit();
}

$idClient = $_SESSION['userId'];
$idProduit = $_POST['idProduit'];
$comment = $_POST['comment'];
$rating = $_POST['rating'];

$stmt = $conn->prepare('INSERT INTO Comments (idClient, idProduit, comment) VALUES (?, ?, ?)');
$stmt->bind_param('iis', $idClient, $idProduit, $comment);

if ($stmt->execute()) {
    $stmt->close();
    $stmt = $conn->prepare('REPLACE INTO Ratings (idClient, idProduit, rating) VALUES (?, ?, ?)');
    $stmt->bind_param('iii', $idClient, $idProduit, $rating);
    $stmt->execute();
}

$stmt->close();
$conn->close();

header('location:productdetails.php?idProduit=' . $idProduit);
?>
