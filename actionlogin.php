<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include('connexion.php');
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check Clients table
    $stmt = $conn->prepare("SELECT idClient, password FROM Clients WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($userId, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['userId'] = $userId;
            $_SESSION['email'] = $email;
            $_SESSION['userType'] = 'client';

            // Redirect to home page
            header("Location: index.php");
            exit();
        } else {
            header("Location: login.php?error=Invalid password");
            exit();
        }
    } else {
        // Check Sellers table if not found in Clients table
        $stmt->close();

        $stmt = $conn->prepare("SELECT idSeller, password FROM Sellers WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($userId, $hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                $_SESSION['userId'] = $userId;
                $_SESSION['email'] = $email;
                $_SESSION['userType'] = 'seller';
                // Redirect to home page
                header("Location: index.php");
                exit();
            } else {
                header("Location: login.php?error=Invalid password");
                exit();
            }
        } else {
            header("Location: login.php?error=No account found with that email");
            exit();
        }
    }

    $stmt->close();
    $conn->close();
}
?>
