<?php
session_start();
include('connexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_SESSION['userId'];

    $stmt = $conn->prepare('SELECT nom, prenom, email, city, password FROM Clients WHERE idClient = ?');
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $stmt->bind_result($nom, $prenom, $email, $city, $password);
    $stmt->fetch();
    $stmt->close();

    $phone = $_POST['phone'];
    $adresse = $_POST['adresse'];
    $stmt = $conn->prepare('INSERT INTO Sellers (nom, prenom, phone, email, adresse, city, password) VALUES (?, ?, ?, ?, ?, ?, ?)');
    $stmt->bind_param('ssissss', $nom, $prenom, $phone, $email, $adresse, $city, $password);

    if ($stmt->execute()) {
        $stmt = $conn->prepare('DELETE FROM Clients WHERE idClient = ?');
        $stmt->bind_param('i', $userId);
        $stmt->execute();

        $_SESSION['isSeller'] = true;

        header('location:myaccount.php');
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
