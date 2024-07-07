<?php
session_start();
if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}

include('connexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_SESSION['userId'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $adresse = $_POST['adresse'];
    $city = $_POST['city'];

    $stmt = $conn->prepare("UPDATE Clients SET nom = ?, prenom = ?, phone = ?, email = ?, adresse = ?, city = ? WHERE idClient = ?");
    $stmt->bind_param("ssisssi", $nom, $prenom, $phone, $email, $adresse, $city, $userId);

    if ($stmt->execute() === TRUE) {
        // Redirect back to myaccount.php after successful update
        header("Location: myaccount.php");
        exit();
    } else {
        echo "Error updating record: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
