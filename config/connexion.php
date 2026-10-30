
<?php

// Paramètres de connexion à la base de données
$host = "localhost";
$dbname = "gestion_etudians";
$username = "root";
$password = "";

try {
    // Création de la connexion PDO
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );

    // Configuration des erreurs PDO
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


} catch (PDOException $e) {

    // Affichage du message en cas d'erreur
    die("Erreur de connexion : " . $e->getMessage());

}

?>
