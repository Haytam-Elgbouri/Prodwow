<?php
session_start();
include('connexion.php');

if (!isset($_SESSION['adminId'])) {
    header('location:adminlogin.php');
    exit();
}

if (isset($_GET['idClient'])) {
    $idClient = $_GET['idClient'];

    $conn->begin_transaction();

    try {
        $stmt = $conn->prepare("DELETE FROM Comments WHERE idClient = ?");
        $stmt->bind_param("i", $idClient);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("DELETE FROM Reviews WHERE idClient = ?");
        $stmt->bind_param("i", $idClient);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("DELETE FROM Ratings WHERE idClient = ?");
        $stmt->bind_param("i", $idClient);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("DELETE FROM Payments WHERE idClient = ?");
        $stmt->bind_param("i", $idClient);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("DELETE FROM Clients WHERE idClient = ?");
        $stmt->bind_param("i", $idClient);
        $stmt->execute();
        $stmt->close();

        $conn->commit();

        echo "Client deleted successfully.";
    } catch (Exception $e) {
        $conn->rollback();
        echo "Error deleting client: " . $e->getMessage();
    }

    $conn->close();

    header('Location: admindashboard.php');
    exit();
}
?>
