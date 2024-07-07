<?php
session_start();
include('connexion.php');

if (!isset($_SESSION['adminId'])) {
    header('location:adminlogin.php');
    exit();
}

if (isset($_GET['idSeller'])) {
    $idSeller = $_GET['idSeller'];

    $conn->begin_transaction();

    try {
        $stmt = $conn->prepare("DELETE FROM Products WHERE idSeller = ?");
        $stmt->bind_param("i", $idSeller);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("DELETE FROM Sellers WHERE idSeller = ?");
        $stmt->bind_param("i", $idSeller);
        $stmt->execute();
        $stmt->close();

        $conn->commit();

        echo "Seller deleted successfully.";
    } catch (Exception $e) {
        $conn->rollback();
        echo "Error deleting seller: " . $e->getMessage();
    }

    $conn->close();

    header('Location: admindashboard.php');
    exit();
}
?>
