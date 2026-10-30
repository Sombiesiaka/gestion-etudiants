
<?php

// Connexion à la base de données
require_once "config/connexion.php";

// Initialisation
$resultats = [];
$nom = "";

// Vérifier si une recherche a été effectuée
if (isset($_GET['nom'])) {

    // Récupération du nom
    $nom = trim($_GET['nom']);

    // Préparation de la requête SQL
    $sql = "SELECT * FROM etudiants WHERE nom LIKE :nom";

    $stmt = $pdo->prepare($sql);

    // Exécution de la requête
    $stmt->execute([
        ':nom' => "%" . $nom . "%"
    ]);

    // Récupération des résultats
    $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Rechercher un étudiant</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            padding: 30px;
        }

        .container {
            width: 80%;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
        }

        h2 {
            text-align: center;
        }

        input {
            padding: 10px;
            width: 70%;
        }

        button {
            padding: 10px 20px;
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }

        table {
            width: 100%;
            margin-top: 25px;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        th {
            background: #007bff;
            color: white;
        }
    </style>

</head>

<body>

<div class="container">

    <h2>Rechercher un étudiant</h2>

    <!-- Formulaire de recherche -->
    <form method="GET" action="">

        <input
            type="text"
            name="nom"
            placeholder="Entrez le nom de l'étudiant"
            value="<?= htmlspecialchars($nom) ?>"
            required
        >

        <button type="submit">Rechercher</button>

    </form>

    <?php if (isset($_GET['nom'])) : ?>

        <?php if (!empty($resultats)) : ?>

            <h3>Résultats de la recherche</h3>

            <table>

                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>E-mail</th>
                    <th>Filière</th>
                    <th>Date d'inscription</th>
                </tr>

                <?php foreach ($resultats as $etudiant) : ?>

                    <tr>
                        <td><?= htmlspecialchars($etudiant['id']) ?></td>
                        <td><?= htmlspecialchars($etudiant['nom']) ?></td>
                        <td><?= htmlspecialchars($etudiant['prenom']) ?></td>
                        <td><?= htmlspecialchars($etudiant['email']) ?></td>
                        <td><?= htmlspecialchars($etudiant['filiere']) ?></td>
                        <td><?= htmlspecialchars($etudiant['date_inscription'] ?? '') ?></td>
                    </tr>

                <?php endforeach; ?>

            </table>

        <?php else : ?>

            <p style="color:red;">
                Aucun étudiant trouvé avec ce nom.
            </p>

        <?php endif; ?>

    <?php endif; ?>

</div>

</body>
</html>
