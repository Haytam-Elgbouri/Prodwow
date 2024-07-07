<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['userId']) || $_SESSION['userType'] != 'seller') {
        header('location:login.php');
        exit();
    }

    include('connexion.php');

    $nom = $_POST['nom'];
    $categorieId = $_POST['categorieId'];
    $prix = $_POST['prix'];
    $description = $_POST['description'];
    $idSeller = $_SESSION['userId'];
    
    // Handle image upload
    $target_dir = "img/imgproduct/";
    $target_file = $target_dir . basename($_FILES["photo"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
    // Check if image file is an actual image or fake image
    $check = getimagesize($_FILES["photo"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        header("Location: addproduct.php?error=File is not an image.");
        exit();
        $uploadOk = 0;
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        header("Location: addproduct.php?error=Sorry, your file was not uploaded.");
            exit();
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
            $photo = $target_file;
            
            // Insert product into the database
            $stmt = $conn->prepare("INSERT INTO Products (nom, categorieId, idSeller, prix, photo, description) VALUES (?, ?, ?, ?, ?,?)");
            $stmt->bind_param("siidss", $nom, $categorieId, $idSeller, $prix, $photo,$description);

            if ($stmt->execute()) {
                header("Location: addproduct.php?error=The product has been added successfully.");
            exit();
            } else {
                header("Location: addproduct.php?error=something went wrong");
            exit();
                
            }
            header("Location: product.php");
            $stmt->close();
            $conn->close();
        } else {
            header("Location: addproduct.php?error=Sorry, there was an error uploading your file.");
            exit();
        }
    }
}
?>
