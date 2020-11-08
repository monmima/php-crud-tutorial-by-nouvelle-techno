<?php
    // On démarre une session
    // Permet d'utiliser la superglobale $_SESSION; permet de partager une variable entre fichiers PHP
    session_start();

    // On inclut la connexion à la base
    require_once("connect.php");

    $sql = "SELECT * FROM `data_tb`";

    // On prépare la requête
    $query = $db->prepare($sql);

    // On exécute la requête
    $query->execute();

    // On stocke le résultat dans un tableau associatif
    // Pour ne pas avoir la totalité des résultats en double
    $result = $query->fetchAll(PDO::FETCH_ASSOC);

    // var_dump($result);

    require("close.php");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des produits</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <main class="container">
        <div class="row">
            <section class="col-12">

            <?php
                if (!empty($_SESSION["erreur"])) {

                    echo '<div class="alert alert-danger" role="alert">
                        '. $_SESSION["erreur"] .'
                    </div>';
                    
                    $_SESSION["erreur"] = "";

                }
            ?>

            <?php
                if (!empty($_SESSION["message"])) {

                    echo '<div class="alert alert-success" role="alert">
                        '. $_SESSION["message"] .'
                    </div>';
                    
                    $_SESSION["message"] = "";

                }
            ?>

                <h1>Liste des produits</h1>
                <table class="table">
                    <thead>
                        <th>ID</th>
                        <th>Produit</th>
                        <th>Prix</th>
                        <th>Nombre</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        <?php
                            // On boucle sur la variable result
                            foreach($result as $produit) {
                        ?>
                        <!-- ?= est forme courte pour "echo" -->
                            <tr>
                                <td><?= $produit["id"] ?></td>
                                <td><?= $produit["produit"] ?></td>
                                <td><?= $produit["prix"] ?></td>
                                <td><?= $produit["nombre"] ?></td>
                                <td><a href="details.php?id=<?= $produit['id'] ?>">Voir les détails</a></td>
                            </tr>
                        <?php
                            }
                        ?>
                    </tbody>
                </table>
                <a href="add.php" title="Ajouter un produit" class="btn btn-primary">Ajouter un produit</a>
            </section>
        </div>
    </main>
</body>
</html>