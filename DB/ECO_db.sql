-- Create the database
CREATE DATABASE Eco_db;

-- Use the database
USE Eco_db;

-- Create Clients table
CREATE TABLE Clients (
    idClient INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL,
    city VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- Create Sellers table
CREATE TABLE Sellers (
    idSeller INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    phone INT NOT NULL,
    email VARCHAR(50) NOT NULL,
    adresse VARCHAR(255) NOT NULL,
    city VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- Create Categories table
CREATE TABLE Categories ( 
    idCategorie INT AUTO_INCREMENT PRIMARY KEY,
    nomCategorie VARCHAR(255) NOT NULL
);

-- Create Products table
CREATE TABLE Products (
    idProduit INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    photo VARCHAR(255) NOT NULL,
    categorieId INT NOT NULL,
    idSeller INT NOT NULL,
    prix FLOAT NOT NULL,
    description VARCHAR(255) NOT NULL,
    FOREIGN KEY (categorieId) REFERENCES Categories(idCategorie),
    FOREIGN KEY (idSeller) REFERENCES Sellers(idSeller)
);

-- Create Cart table
CREATE TABLE Cart (
    idPanier INT AUTO_INCREMENT PRIMARY KEY,
    idClient INT NOT NULL,
    idProduit INT NOT NULL,
    quantity INT NOT NULL,
    FOREIGN KEY (idClient) REFERENCES Clients(idClient),
    FOREIGN KEY (idProduit) REFERENCES Products(idProduit)
);

-- Create Payments table
CREATE TABLE Payments (
    idPayment INT AUTO_INCREMENT PRIMARY KEY,
    idClient INT NOT NULL,
    idCarteBNC INT NOT NULL,
    nomCarteBNC VARCHAR(255) NOT NULL,
    FOREIGN KEY (idClient) REFERENCES Clients(idClient)
);

-- Create Reviews table
CREATE TABLE Reviews (
    idReview INT AUTO_INCREMENT PRIMARY KEY,
    idClient INT NOT NULL,
    idProduit INT NOT NULL,
    review TEXT NOT NULL,
    FOREIGN KEY (idClient) REFERENCES Clients(idClient),
    FOREIGN KEY (idProduit) REFERENCES Products(idProduit)
);

-- Create Comments table
CREATE TABLE Comments (
    idComment INT AUTO_INCREMENT PRIMARY KEY,
    idClient INT NOT NULL,
    idProduit INT NOT NULL,
    comment TEXT NOT NULL,
    FOREIGN KEY (idClient) REFERENCES Clients(idClient),
    FOREIGN KEY (idProduit) REFERENCES Products(idProduit)
);

-- Create Ratings table
CREATE TABLE Ratings (
    idRating INT AUTO_INCREMENT PRIMARY KEY,
    idClient INT NOT NULL,
    idProduit INT NOT NULL,
    rating INT NOT NULL,
    FOREIGN KEY (idClient) REFERENCES Clients(idClient),
    FOREIGN KEY (idProduit) REFERENCES Products(idProduit)
);

-- Create Admins table
CREATE TABLE Admins (
    idAdmin INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- Create AccountControl table
CREATE TABLE AccountControl (
    idControl INT AUTO_INCREMENT PRIMARY KEY,
    idAdmin INT NOT NULL,
    idClient INT,
    idSeller INT,
    action VARCHAR(50) NOT NULL,
    actionDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (idAdmin) REFERENCES Admins(idAdmin),
    FOREIGN KEY (idClient) REFERENCES Clients(idClient),
    FOREIGN KEY (idSeller) REFERENCES Sellers(idSeller)
);
INSERT INTO Admins (nom, prenom, email, password) VALUES
('Yassine', 'AB', 'yassine@example.com', PASSWORD('adminpassword1')),
('Haytam', 'EL', 'haytam@example.com', PASSWORD('adminpassword2'));