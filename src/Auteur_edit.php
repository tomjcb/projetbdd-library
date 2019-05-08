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

<form method="post" action="Auteur_edit.php">
    <div class="row">
        <fieldset>
            <legend>Modifier un auteur</legend>
            <!-- ## Pour conserver la valeur de l'id -->
            <input name="idAuteur" type="hidden" value="<?php if (isset($donnees['idAuteur'])) echo $donnees['idAuteur'] ?>">
            <label>Nom auteur</label>
            <input type="text" name="nomAuteur" size="18" value="<?php if (isset($donnees['nomAuteur'])) echo $donnees['nomAuteur'] ?>" >
            <?php if (isset($erreurs['nomAuteur']))
                echo '<div class="alertdanger">'.$erreurs['nomAuteur'].'</div>';
            ?>

            <label>Prénom auteur</label>
            <input type="text" name="prenomAuteur" size="18" value="<?php if (isset($donnees['prenomAuteur'])) echo $donnees['prenomAuteur'] ?>" >
            <?php if (isset($erreurs['prenomAuteur']))
                echo '<div class="alertdanger">'.$erreurs['prenomAuteur'].'</div>';
            ?>

            <input type="submit" name="ModifierAuteur" value="Modifier" >
        </fieldset>
    </div>
</form>
