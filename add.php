<?php
    // On démarre une session
    // Permet d'utiliser la superglobale $_SESSION; permet de partager une variable entre fichiers PHP
    session_start();

    // $_POST signifie que des informations sont envoyées par le formulaire

    if($_POST) {
        // on vérifie qu'il est défini et pas vide
        if (isset($_POST['produit']) && !empty($_POST['produit'])
        && isset($_POST['prix']) && !empty($_POST['prix'])
        && isset($_POST['nombre']) && !empty($_POST['nombre'])) {

            // On inclut la connexion à la base
            require_once("connect.php");

            // On nettoie les données envoyées
            $produit = strip_tags($_POST["produit"]);
            $prix = strip_tags($_POST["prix"]);
            $nombre = strip_tags($_POST["nombre"]);

            $sql = "INSERT INTO `data_tb` (`produit`, `prix`, `nombre`) VALUES (:produit, :prix, :nombre);";

            $query = $db->prepare($sql);

            $query->bindValue(':produit', $produit, PDO::PARAM_STR);
            $query->bindValue(':prix', $prix, PDO::PARAM_STR);
            $query->bindValue(':nombre', $nombre, PDO::PARAM_INT);

            $query->execute();

            $_SESSION["message"] = "Produit ajouté";

            require("close.php");

            // redirection vers la page d'accueil
            header("Location: index.php");


        } else {
            $_SESSION["erreur"] = "Le formulaire est incomplet"; 
        }
    }


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un produit</title>

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

                <h1>Ajouter un produit</h1>
 
                <!-- Si on ne met pas d'action, c'est la page elle-même qui va être chargée -->
                <form action="" method="post">
                    <div class="form-group">
                        <label for="produit">Produit</label>
                        <input type="text" name="produit" id="produit" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="prix">Prix</label>
                        <input type="text" name="prix" id="prix" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="number" name="nombre" id="nombre" class="form-control">
                    </div>

                    <button class="btn btn-primary">Envoyer</button>

                </form>

            </section>
        </div>
    </main>
</body>
</html>