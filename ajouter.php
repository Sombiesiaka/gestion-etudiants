
<?php

// Connexion à la base de données
require_once "config/connexion.php";

// Initialisation des variables
$erreurs = [];
$message = "";

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Récupération des données du formulaire
    $nom = trim($_POST["nom"] ?? "");
    $prenom = trim($_POST["prenom"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $filiere = trim($_POST["filiere"] ?? "");
    $date_inscription = $_POST["date_inscription"] ?? "";

    // Vérification des champs obligatoires
    if (empty($nom) || empty($prenom) || empty($email) || empty($filiere)) {
        $erreurs[] = "Veuillez remplir tous les champs obligatoires.";
    }

    // Vérification du format de l'e-mail
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreurs[] = "Veuillez saisir une adresse e-mail valide.";
    }

    // Insertion dans la base de données si aucune erreur
    if (empty($erreurs)) {

        try {

            // Préparation de la requête SQL
            $sql = "INSERT INTO etudiants
                    (nom, prenom, email, filiere, date_inscription)
                    VALUES (:nom, :prenom, :email, :filiere, :date_inscription)";

            $stmt = $pdo->prepare($sql);

            // Exécution de la requête
            $stmt->execute([
                ':nom' => $nom,
                ':prenom' => $prenom,
                ':email' => $email,
                ':filiere' => $filiere,
                ':date_inscription' => $date_inscription ?: null
            ]);

            $message = "Étudiant ajouté avec succès !";

        } catch (PDOException $e) {

            $erreurs[] = "Erreur lors de l'ajout de l'étudiant.";

        }
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Ajouter un étudiant</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
        }

        .container {
            width: 400px;
            margin: 50px auto;
            padding: 25px;
            background: white;
            border-radius: 10px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        label {
            display: block;
            margin-top: 15px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 12px;
            margin-top: 20px;
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }

        .erreur {
            color: red;
        }

        .succes {
            color: green;
        }
    </style>
</head>

<body>

<div class="container">

    <h2>Ajouter un étudiant</h2>

    <!-- Affichage des erreurs -->
    <?php foreach ($erreurs as $erreur) : ?>
        <p class="erreur">
            <?= htmlspecialchars($erreur) ?>
        </p>
    <?php endforeach; ?>

    <!-- Message de succès -->
    <?php if (!empty($message)) : ?>
        <p class="succes">
            <?= htmlspecialchars($message) ?>
        </p>
    <?php endif; ?>

    <form method="POST" action="">

        <label>Nom :</label>
        <input type="text" name="nom" required>

        <label>Prénom :</label>
        <input type="text" name="prenom" required>

        <label>E-mail :</label>
        <input type="email" name="email" required>

        <label>Filière :</label>
        <input type="text" name="filiere" required>

        <label>Date d'inscription :</label>
        <input type="date" name="date_inscription">

        <button type="submit">Ajouter l'étudiant</button>

    </form>

</div>

</body>
</html>
