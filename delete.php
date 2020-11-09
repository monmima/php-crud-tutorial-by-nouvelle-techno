<?php
    // On démarre une session
    // Permet d'utiliser la superglobale $_SESSION; permet de partager une variable entre fichiers PHP
    session_start();

    // isset = est-ce que ça existe?
    // Est-ce que l'id existe et n'est pas vide dans l'URL?
    if (isset($_GET["id"]) && !empty($_GET["id"])) {
        require_once("connect.php");

        // 1. On vérifie que l'ID existe.

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
            die();
        }

        // 2. On efface l'ID une fois qu'on s'est assuré de son existence.
    
        $sql = "DELETE FROM `data_tb` WHERE `id` = :id;";

        // On prépare la requête

        $query = $db->prepare($sql);

        // On "accroche" les paramètres (id)
        // PDO::PARAM_INT = pour s'assurer qu'il s'agit d'un entier
        $query->bindValue(":id", $id, PDO::PARAM_INT);

        // On exécute la requête
        $query->execute();

        $_SESSION["message"] = "Produit supprimé.";
        header("Location: index.php");

    } else {
        $_SESSION["erreur"] = "URL invalide";
        header("Location: index.php");
    }
?>