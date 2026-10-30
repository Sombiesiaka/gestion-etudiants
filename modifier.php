
<?php

// Connexion à la base de données
require_once "config/connexion.php";

$erreurs = [];
$message = "";
$etudiant = null;

// Récupération de l'identifiant
$id = $_GET['id'] ?? $_POST['id'] ?? "";

// Vérification de l'identifiant
if (!filter_var($id, FILTER_VALIDATE_INT) || $id <= 0) {
    die("Identifiant invalide.");
}

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Récupération des nouvelles informations
    $nom = trim($_POST['nom'] ?? "");
    $prenom = trim($_POST['prenom'] ?? "");
    $email = trim($_POST['email'] ?? "");
    $filiere = trim($_POST['filiere'] ?? "");
    $date_inscription = $_POST['date_inscription'] ?? "";

    // Vérification des champs obligatoires
    if (empty($nom) || empty($prenom) || empty($email) || empty($filiere)) {
        $erreurs[] = "Tous les champs obligatoires doivent être remplis.";
    }

    // Vérification de l'e-mail
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreurs[] = "Adresse e-mail invalide.";
    }

    // Modification si aucune erreur
    if (empty($erreurs)) {

        try {

            $sql = "UPDATE etudiants
                    SET nom = :nom,
                        prenom = :prenom,
                        email = :email,
                        filiere = :filiere,
                        date_inscription = :date_inscription
                    WHERE id = :id";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ':nom' => $nom,
                ':prenom' => $prenom,
                ':email' => $email,
                ':filiere' => $filiere,
                ':date_inscription' => $date_inscription ?: null,
                ':id' => $id
            ]);

            $message = "Étudiant modifié avec succès !";

        } catch (PDOException $e) {

            $erreurs[] = "Erreur lors de la modification.";

        }
    }
}

// Récupération des informations de l'étudiant
$stmt = $pdo->prepare("SELECT * FROM etudiants WHERE id = :id");
$stmt->execute([':id' => $id]);

$etudiant = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$etudiant) {
    die("Étudiant introuvable.");
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Modifier un étudiant</title>

    <style>
        body {
            font-family: Arial;
            background: #f2f2f2;
        }

        .container {
            width: 400px;
            margin: 50px auto;
            padding: 25px;
            background: white;
            border-radius: 10px;
        }

        label {
            display: block;
            margin-top: 15px;
        }

        input {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 12px;
            margin-top: 20px;
            background: green;
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

    <h2>Modifier un étudiant</h2>

    <?php foreach ($erreurs as $erreur) : ?>
        <p class="erreur">
            <?= htmlspecialchars($erreur) ?>
        </p>
    <?php endforeach; ?>

    <?php if (!empty($message)) : ?>
        <p class="succes">
            <?= htmlspecialchars($message) ?>
        </p>
    <?php endif; ?>

    <form method="POST" action="">

        <input type="hidden" name="id"
               value="<?= htmlspecialchars($id) ?>">

        <label>Nom :</label>
        <input type="text" name="nom" required
               value="<?= htmlspecialchars($etudiant['nom']) ?>">

        <label>Prénom :</label>
        <input type="text" name="prenom" required
               value="<?= htmlspecialchars($etudiant['prenom']) ?>">

        <label>E-mail :</label>
        <input type="email" name="email" required
               value="<?= htmlspecialchars($etudiant['email']) ?>">

        <label>Filière :</label>
        <input type="text" name="filiere" required
               value="<?= htmlspecialchars($etudiant['filiere']) ?>">

        <label>Date d'inscription :</label>
        <input type="date" name="date_inscription"
               value="<?= htmlspecialchars($etudiant['date_inscription'] ?? '') ?>">

        <button type="submit">Enregistrer les modifications</button>

    </form>

    <p>
        <a href="index.php">Retour à la liste</a>
    </p>

</div>

</body>
</html>

