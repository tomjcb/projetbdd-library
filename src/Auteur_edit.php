<?php include("v_head.php");  ?>
<?php include ("v_nav.php");  ?>
<?php include ("connexion_bdd.php");

// ### Verification de l'identifiant:
if (isset($_GET['id']) and is_numeric($_GET['id'])){
    // ## Récupère le premier enregistement
    $id = htmlentities($_GET['id']);
    $ma_requete_SQL ="SELECT au.idAuteur, au.nomAuteur, au.prenomAuteur
                        FROM AUTEUR au
                        WHERE au.idAuteur=".$id.";";
    $reponse = $bdd->query($ma_requete_SQL);
    $donnees = $reponse->fetch();
}

if (isset($_POST['idAuteur']) and isset($_POST['nomAuteur']) and isset($_POST['prenomAuteur'])){

    $donnees['idAuteur'] = $_POST['idAuteur'];
    $donnees['nomAuteur'] = htmlentities($_POST['nomAuteur']);
    $donnees['prenomAuteur'] = htmlentities($_POST['prenomAuteur']);

    if (! preg_match("/^[A-Za-z]{2,}/", $donnees['nomAuteur']))
        $erreurs['nomAuteur'] = 'Nom composé de 2 lettres minimum';
    if (! preg_match("/^[A-Za-z]{2,}/", $donnees['prenomAuteur']))
        $erreurs['prenomAuteur'] = 'Prenom composé de 2 lettres minimum';

    if (empty($erreurs)) {
        // ## Accès au modèle:
        $ma_requete_SQL = "UPDATE AUTEUR SET nomAuteur='" . $donnees['nomAuteur'] . "',
        prenomAuteur='" . $donnees['prenomAuteur'] . "'
        WHERE idAuteur =" . $donnees['idAuteur'] . ";";
        // var_dump($ma_requete_SQL);
        $bdd->exec($ma_requete_SQL);
        // ## redirection
        header("Location: Auteur_show.php");
    }
}
?>
<div class="row">
    <div class="title">Modifier un auteur</div>
</div>

<form method="post" action="Auteur_edit.php">
    <div class="row">
        <fieldset class="element-center">
            <!-- ## Pour conserver la valeur de l'id -->
            <input name="idAuteur" type="hidden" value="<?php if (isset($donnees['idAuteur'])) echo $donnees['idAuteur'] ?>">

            <div class="col-md-2 offset-md-5">
                <label>Nom auteur</label>
                <br>
                <input type="text" class="form-control" name="nomAuteur" size="18" value="<?php if (isset($donnees['nomAuteur'])) echo $donnees['nomAuteur'] ?>" >
                <?php if (isset($erreurs['nomAuteur']))
                    echo '<br><div class="alert alert-danger">'.$erreurs['nomAuteur'].'</div>';
                ?>
            </div>

            <br><br>

            <div class="col-md-2 offset-md-5">
                <label>Prénom auteur</label>
                <br>
                <input type="text" class="form-control" name="prenomAuteur" size="18" value="<?php if (isset($donnees['prenomAuteur'])) echo $donnees['prenomAuteur'] ?>" >
                <?php if (isset($erreurs['prenomAuteur']))
                    echo '<br><div class="alert alert-danger">'.$erreurs['prenomAuteur'].'</div>';
                ?>
            </div>

            <br><br>

            <input type="submit" class="btn btn-info" name="ModifierAuteur" value="Modifier" >
        </fieldset>
    </div>
</form>

<?php include ('v_foot.php'); ?>


