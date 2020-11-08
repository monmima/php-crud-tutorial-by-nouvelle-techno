<?php
    // On démarre une session
    // Permet d'utiliser la superglobale $_SESSION; permet de partager une variable entre fichiers PHP
    session_start();

    // isset = est-ce que ça existe?
    // Est-ce que l'id existe et n'est pas vide dans l'URL?
    if (isset($_GET["id"]) && !empty($_GET["id"])) {
        require_once("connect.php");

        // On nettoie l'ID envoyé
        $id = strip_tags($_GET["id"]);
        
        $sql = "SELECT * FROM `data_tb` WHERE `id` = :id;";

        // On prépare la requête

        $query = $db->prepare($sql);

        // On "accroche" les paramètres (id)
        // PDO::PARAM_INT = pour s'assurer qu'il s'agit d'un entier
        $query->bindValue(":id", $id, PDO::PARAM_INT);

        // On exécute la requête
        $query->execute();

        // On récupère le produit
        // "fetch()" parce qu'on a un seul produit à récupérer
        $produit = $query->fetch();

        // On vérifie si le produit existe
        if (!$produit) {
            $_SESSION["erreur"] = "Cet ID n'existe pas.";
            header("Location: index.php");
        }
    } else {
        $_SESSION["erreur"] = "URL invalide";
        header("Location: index.php");
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du produit</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <main class="container">
        <div class="row">
            <section class="col-12">
                <h1>Détails du produit <?= $produit["produit"] ?></h1>
                <p>ID&nbsp;: <?= $produit["id"] ?></p>
                <p>Produit&nbsp;: <?= $produit["produit"] ?></p>
                <p>Prix&nbsp;: <?= $produit["prix"] ?></p>
                <p>Nombre&nbsp;: <?= $produit["nombre"] ?></p>
                <p>
                    <a href="index.php" title="Retour vers l'index">Retour</a>
                    <a href="edit.php?:id=<?= $produit["id"] ?>" title="Modifier l'entrée">Modifier</a>
                </p>
            </section>
        </div>
    </main>
</body>
</html>