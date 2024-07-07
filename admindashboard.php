<?php
session_start();
include('connexion.php');

if (!isset($_SESSION['adminId'])) {
    header('location:adminlogin.php');
    exit();
}

// Fetch stats
$clientCount = $conn->query("SELECT COUNT(*) FROM Clients")->fetch_row()[0];
$sellerCount = $conn->query("SELECT COUNT(*) FROM Sellers")->fetch_row()[0];
$productCount = $conn->query("SELECT COUNT(*) FROM Products")->fetch_row()[0];
$categoryCount = $conn->query("SELECT COUNT(DISTINCT categorieId) FROM Products")->fetch_row()[0];

// Fetch all clients
$clients = $conn->query("SELECT * FROM Clients");

// Fetch all sellers
$sellers = $conn->query("SELECT * FROM Sellers");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include("header.php"); ?>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="productdetails.css">

    <style>
        table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
    background-color: var(--color4);
}

table, th, td {
    border: 1px solid var(--color3);
    text-align: left;
    padding: 8px;
}

th {
    background-color: var(--color3);
    color: white;
}

tr:nth-child(even) {
    background-color: var(--color4light);
}

a {
    color: var(--color5);
    text-decoration: none;
    transition: 0.3s;
}

a:hover {
    color: var(--color2);
}
    </style>
</head>
<body>
    <?php include("navbar.php"); ?>
    <div class="container">
    <div class="dashboardinfo">
        <h2>Admin Dashboard</h2>
        <div class="siteinfo">
        <h3>Clients : <?php echo $clientCount; ?></h3>
        <h3>Sellers : <?php echo $sellerCount; ?></h3>
        <h3>Products : <?php echo $productCount; ?></h3>
        <h3>Categories : <?php echo $categoryCount; ?></h3>
        </div>
        <h3>Clients</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>City</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $clients->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['idClient']; ?></td>
                    <td><?php echo $row['nom'] . ' ' . $row['prenom']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['city']; ?></td>
                    <td><a href="deleteclient.php?idClient=<?php echo $row['idClient']; ?>" onclick="return confirm('Are you sure you want to delete this client?');">Delete</a></td>
                </tr>
            <?php endwhile; ?>
        </table>

        <h3>Sellers</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>City</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $sellers->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['idSeller']; ?></td>
                    <td><?php echo $row['nom'] . ' ' . $row['prenom']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['phone']; ?></td>
                    <td><?php echo $row['city']; ?></td>
                    <td><a href="deleteseller.php?idSeller=<?php echo $row['idSeller']; ?>" onclick="return confirm('Are you sure you want to delete this seller?');">Delete</a></td>
                </tr>
            <?php endwhile; ?>
        </table>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>
