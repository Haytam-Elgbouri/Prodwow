<?php
include('connexion.php');

$admins = [
    [
        'email' => 'admin1@gmail.com',
        'password' => 'admin2002', // Plain text password
        'nom' => 'Yassine',
        'prenom' => 'AB'
    ],
    [
        'email' => 'admin2@gmail.com',
        'password' => 'admin2002', // Plain text password
        'nom' => 'Haytam',
        'prenom' => 'EL'
    ]
];

foreach ($admins as $admin) {
    $hashed_password = password_hash($admin['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO Admins (email, password, nom, prenom) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $admin['email'], $hashed_password, $admin['nom'], $admin['prenom']);

    if ($stmt->execute()) {
        echo "Admin " . htmlspecialchars($admin['email']) . " inserted successfully.<br>";
    } else {
        echo "Error inserting admin " . htmlspecialchars($admin['email']) . ": " . htmlspecialchars($stmt->error) . "<br>";
    }

    $stmt->close();
}

$conn->close();
?>
