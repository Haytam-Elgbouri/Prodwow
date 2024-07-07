<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['password'] != $_POST['Cpassword']) {
        header('Location: registration.php?err=1&nom=' . urlencode($_POST['nom'])
                                            . '&prenom=' . urlencode($_POST['prenom'])
                                            . '&email=' . urlencode($_POST['email'])
                                            . '&city=' . urlencode($_POST['city']));
        exit();
    } else {
        include 'connexion.php';
        
        // Check if email exists in either Clients or Sellers
        $stmt = $conn->prepare('SELECT COUNT(*) FROM Clients WHERE email = ?');
        $stmt->bind_param('s', $_POST['email']);
        $stmt->execute();
        $stmt->bind_result($exists);
        $stmt->fetch();
        $stmt->close();
        
        if ($exists > 0) {
            header('Location: registration.php?err=2&nom=' . urlencode($_POST['nom'])
                                                . '&prenom=' . urlencode($_POST['prenom'])
                                                . '&email=' . urlencode($_POST['email'])
                                                . '&city=' . urlencode($_POST['city']));
            exit();
        }
        
        $stmt = $conn->prepare('SELECT COUNT(*) FROM Sellers WHERE email = ?');
        $stmt->bind_param('s', $_POST['email']);
        $stmt->execute();
        $stmt->bind_result($exists);
        $stmt->fetch();
        $stmt->close();

        if ($exists > 0) {
            header('Location: registration.php?err=2&nom=' . urlencode($_POST['nom'])
                                                . '&prenom=' . urlencode($_POST['prenom'])
                                                . '&email=' . urlencode($_POST['email'])
                                                . '&city=' . urlencode($_POST['city']));
            exit();
        }
        
        // Insert new client
        $stmt = $conn->prepare('INSERT INTO Clients (nom, prenom, email, city, password) VALUES (?, ?, ?, ?, ?)');
        $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt->bind_param('sssss', $_POST['nom'], $_POST['prenom'], $_POST['email'], $_POST['city'], $hashed_password);
        
        if ($stmt->execute()) {
            header('Location: login.php?registration=success');
            exit();
        } else {
            header('Location: registration.php?err=3');
            exit();
        }
    }
}
?>
