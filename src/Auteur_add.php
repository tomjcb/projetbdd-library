<?php include("v_head.php");  ?>
<?php include ("v_nav.php");  ?>
<?php include ("connexion_bdd.php");

// traitement
if (!empty($_POST["nomAuteur"]) AND isset($_POST['prenomAuteur'])) {
    // controle des données
    $donnees['nomAuteur'] = htmlentities($_POST['nomAuteur']);
    $donnees['prenomAuteur'] = htmlentities($_POST['prenomAuteur']);

    if (! preg_match("/^[A-Za-z]{2,}/", $donnees['nomAuteur'])) {
        $erreurs['nomAuteur'] = 'Nom composé de 2 lettres minimum';
    }
    if (!empty($_POST['prenomAuteur'])) {
        if (! preg_match("/^[A-Za-z]{2,}/", $donnees['prenomAuteur'])){
            $erreurs['prenomAuteur'] = 'Prenom composé de 2 lettres minimum';
        }
    }

    if (empty($erreurs)) {
        // accès au modèle
        if (isset($donnees['prenomAuteur'])) {
            $ma_requete_SQL = "INSERT INTO AUTEUR (idAuteur, nomAuteur, prenomAuteur)
      VALUES (NULL, '".$donnees['nomAuteur']."','".$donnees['prenomAuteur']."'); ";
            $bdd->exec($ma_requete_SQL);
        }else {
            $ma_requete_SQL = "INSERT INTO AUTEUR (idAuteur, nomAuteur)
      VALUES (NULL, '".$donnees['nomAuteur']."'); ";
            $bdd->exec($ma_requete_SQL);
        }


        // ## Redirection
        header("Location: Auteur_show.php");
    }
}

?>

<body>
<form method="post" action="Auteur_add.php">
    <div class="row">
        <fieldset>
            <legend>Ajouter un auteur</legend>

            <div class="col-xs-12 col-md-12">
                <div class="form-group">
                    <label>Nom de l'Auteur : </label>
                    <input class="form-control" type="text" name="nomAuteur" value="<?php if(isset($donnees["nomAuteur"])) echo $donnees['nomAuteur']; ?>">
                    <small class="error"><?php if (isset($erreurs["nomAuteur"])) echo $erreurs["nomAuteur"]; ?></small>
                </div>
            </div>

            <div class="col-xs-12 col-md-12">
                <div class="form-group">
                    <label>Prénom : </label>
                    <input class="form-control" type="text" name="prenomAuteur" value="<?php if(isset($_POST["prenomAuteur"])) echo $donnees['prenomAuteur']; ?>">
                    <small class="error"><?php if (isset($erreurs["prenomAuteur"])) echo $erreurs["prenomAuteur"]; ?></small>
                </div>
            </div>

            <input name="addAuteur" type="submit" value="Ajouter un auteur">
        </fieldset>
    </div>
</form>
</body>
