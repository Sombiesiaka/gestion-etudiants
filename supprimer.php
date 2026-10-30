
<?php

// Connexion à la base de données
require_once "config/connexion.php";

$message = "";

// Récupération de l'identifiant
$id = $_GET['id'] ?? $_POST['id'] ?? "";

// Vérification de l'identifiant
if (!filter_var($id, FILTER_VALIDATE_INT) || $id <= 0) {
    die("Identifiant invalide.");
}

// Vérifier si l'utilisateur a confirmé la suppression
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirmer'])) {

    try {

        // Préparation de la requête SQL
        $sql = "DELETE FROM etudiants WHERE id = :id";

        $stmt = $pdo->prepare($sql);

        // Exécution de la requête
        $stmt->execute([':id' => $id]);

        if ($stmt->rowCount() > 0) {
            $message = "Étudiant supprimé avec succès !";
        } else {
            $message = "Aucun étudiant trouvé avec cet identifiant.";
        }

    } catch (PDOException $e) {

        die("Erreur lors de la suppression.");

    }

} else {

    // Récupération des informations de l'étudiant
    $sql = "SELECT * FROM etudiants WHERE id = :id";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([':id' => $id]);

    $etudiant = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$etudiant) {
        die("Étudiant introuvable.");
    }

}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Supprimer un étudiant</title>

    <style>
        body {
            font-family: Arial;
            background: #f2f2f2;
        }

        .container {
            width: 400px;
            margin: 100px auto;
            padding: 25px;
            background: white;
            text-align: center;
            border-radius: 10px;
        }

        button {
            padding: 12px 20px;
            background: red;
            color: white;
            border: none;
            cursor: pointer;
        }

        a {
            display: inline-block;
            padding: 12px 20px;
            background: gray;
            color: white;
            text-decoration: none;
        }

        .succes {
            color: green;
        }
    </style>

</head>

<body>

<div class="container">

    <?php if (!empty($message)) : ?>

        <h2 class="succes">
            <?= htmlspecialchars($message) ?>
        </h2>

        <a href="index.php">Retour à la liste</a>

    <?php else : ?>

        <h2>Confirmation de suppression</h2>

        <p>
            Voulez-vous vraiment supprimer cet étudiant ?
        </p>

        <p>
            <strong>
                <?= htmlspecialchars($etudiant['nom']) ?>
                <?= htmlspecialchars($etudiant['prenom']) ?>
            </strong>
        </p>

        <form method="POST" action="">

            <input type="hidden" name="id"
                   value="<?= htmlspecialchars($id) ?>">

            <button type="submit" name="confirmer">
                Oui, supprimer
            </button>

            <a href="index.php">Annuler</a>

        </form>

    <?php endif; ?>

</div>

</body>
</html>
