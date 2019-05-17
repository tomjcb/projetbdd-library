<?php include("v_head.php");  ?>
<?php include ("v_nav.php");  ?>
<?php include ("connexion_bdd.php");

// traitement
if (isset($_POST["nomAuteur"]) AND isset($_POST['prenomAuteur'])) {
    // controle des données
    $donnees['nomAuteur'] = htmlentities($_POST['nomAuteur']);
    $donnees['prenomAuteur'] = htmlentities($_POST['prenomAuteur']);

    if (! preg_match("/^[A-Za-z]{2,}/", $donnees['nomAuteur'])) {
        $erreurs['nomAuteur'] = 'Nom composé de 2 lettres minimum';
    }
    if (! preg_match("/^[A-Za-z]{2,}/", $donnees['prenomAuteur'])){
        $erreurs['prenomAuteur'] = 'Prenom composé de 2 lettres minimum';
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

<div class="row">
    <div class="title">Ajouter un auteur</div>
</div>

<form method="post" action="Auteur_add.php">
    <div class="row">
        <fieldset class="element-center">

            <div class="col-md-2 offset-md-5">
                <label>Nom de l'Auteur :</label>
                <br>
                <input class="form-control" type="text" name="nomAuteur" value="<?php if(isset($donnees["nomAuteur"])) echo $donnees['nomAuteur']; ?>">
                <?php if (isset($erreurs['nomAuteur']))
                    echo '<br><div class="alert alert-danger">'.$erreurs['nomAuteur'].'</div>';
                ?>
            </div>

            <br><br>

            <div class="col-md-2 offset-md-5">
                <label>Prénom : </label>
                <br>
                <input class="form-control" type="text" name="prenomAuteur" value="<?php if(isset($_POST["prenomAuteur"])) echo $donnees['prenomAuteur']; ?>">
                <?php if (isset($erreurs['prenomAuteur']))
                    echo '<br><div class="alert alert-danger">'.$erreurs['prenomAuteur'].'</div>';
                ?>
            </div>

            <br><br>

            <input name="addAuteur" class="btn btn-info" type="submit" value="Ajouter un auteur">
        </fieldset>
    </div>
</form>

<?php include ("v_foot.php");