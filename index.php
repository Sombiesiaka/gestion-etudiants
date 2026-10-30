
<?php

// Connexion à la base de données
require_once "config/connexion.php";

try {

    // Récupération de tous les étudiants
    $sql = "SELECT * FROM etudiants ORDER BY id DESC";

    $stmt = $pdo->query($sql);

    // Stockage des résultats
    $etudiants = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {

    die("Erreur lors de la récupération : " . $e->getMessage());

}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Liste des étudiants</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            padding: 30px;
        }

        h2 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }

        th {
            background: #007bff;
            color: white;
        }

        .modifier {
            color: white;
            background: green;
            padding: 7px 12px;
            text-decoration: none;
            border-radius: 5px;
        }

        .supprimer {
            color: white;
            background: red;
            padding: 7px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .ajouter {
            display: inline-block;
            margin-bottom: 15px;
            padding: 10px 15px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>

</head>

<body>

    <h2>Liste des étudiants</h2>

    <a href="ajouter.php" class="ajouter">
        Ajouter un étudiant
    </a>

    <table>

        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>E-mail</th>
            <th>Filière</th>
            <th>Date d'inscription</th>
            <th>Actions</th>
        </tr>

        <?php foreach ($etudiants as $etudiant) : ?>

        <tr>

            <td><?= htmlspecialchars($etudiant['id']) ?></td>

            <td><?= htmlspecialchars($etudiant['nom']) ?></td>

            <td><?= htmlspecialchars($etudiant['prenom']) ?></td>

            <td><?= htmlspecialchars($etudiant['email']) ?></td>

            <td><?= htmlspecialchars($etudiant['filiere']) ?></td>

            <td><?= htmlspecialchars($etudiant['date_inscription'] ?? '') ?></td>

            <td>

                <!-- Bouton Modifier -->
                <a href="modifier.php?id=<?= $etudiant['id'] ?>"
                   class="modifier">
                    Modifier
                </a>

                <!-- Bouton Supprimer -->
                <form action="supprimer.php" method="POST"
                      style="display:inline;"
                      onsubmit="return confirm('Voulez-vous vraiment supprimer cet étudiant ?');">

                    <input type="hidden" name="id"
                           value="<?= htmlspecialchars($etudiant['id']) ?>">

                    <button type="submit" class="supprimer">
                        Supprimer
                    </button>

                </form>

            </td>

        </tr>

        <?php endforeach; ?>

    </table>

    <?php if (empty($etudiants)) : ?>
        <p style="text-align:center;">
            Aucun étudiant enregistré.
        </p>
    <?php endif; ?>

</body>
</html>
