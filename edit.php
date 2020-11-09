<?php
    // On démarre une session
    // Permet d'utiliser la superglobale $_SESSION; permet de partager une variable entre fichiers PHP
    session_start();

    // $_POST signifie que des informations sont envoyées par le formulaire

    if($_POST) {
        // on vérifie qu'il est défini et pas vide
        if (isset($_POST['id']) && !empty($_POST['id'])
        && isset($_POST['produit']) && !empty($_POST['produit'])
        && isset($_POST['prix']) && !empty($_POST['prix'])
        && isset($_POST['nombre']) && !empty($_POST['nombre'])) {

            // On inclut la connexion à la base
            require_once("connect.php");

            // On nettoie les données envoyées
            $id = strip_tags($_POST["id"]); // ligne changée (ajoutée pour être plus exact)
            $produit = strip_tags($_POST["produit"]);
            $prix = strip_tags($_POST["prix"]);
            $nombre = strip_tags($_POST["nombre"]);

            $sql = "UPDATE `data_tb` SET `produit`=:produit, `prix`=:prix, `nombre`=:nombre WHERE `id`=:id;"; // ligne changée

            $query = $db->prepare($sql);

            $query->bindValue(':id', $id, PDO::PARAM_INT); // ligne changée (ajoutée pour être plus exact)
            $query->bindValue(':produit', $produit, PDO::PARAM_STR);
            $query->bindValue(':prix', $prix, PDO::PARAM_STR);
            $query->bindValue(':nombre', $nombre, PDO::PARAM_INT);

            $query->execute();

            $_SESSION["message"] = "Produit modifié"; // ligne changée

            require("close.php");

            // redirection vers la page d'accueil
            header("Location: index.php");


        } else {
            $_SESSION["erreur"] = "Le formulaire est incomplet"; 
        }
    }
?>

<?php
    // Cette partie du code (dans ces balises PHP) sert à répondre à la question: Est-ce que j'ai un ID?

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
    <title>Modifier un produit</title>

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

                <h1>Modifier un produit</h1>
 
                <!-- Si on ne met pas d'action, c'est la page elle-même qui va être chargée -->
                <form action="" method="post">
                    <div class="form-group">
                        <label for="produit">Produit</label>
                        <input type="text" name="produit" id="produit" class="form-control" value="<?= $produit["produit"] ?>">
                    </div>

                    <div class="form-group">
                        <label for="prix">Prix</label>
                        <input type="text" name="prix" id="prix" class="form-control" value="<?= $produit["prix"] ?>">
                    </div>

                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="number" name="nombre" id="nombre" class="form-control" value="<?= $produit["nombre"] ?>">
                    </div>

                    <input type="hidden" value="<?= $produit["id"] ?>" name="id">

                    <button class="btn btn-primary">Envoyer</button>

                </form>

            </section>
        </div>
    </main>
</body>
</html>