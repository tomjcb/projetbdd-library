<?php include("v_head.php");  ?>
<?php include ("v_nav.php");  ?>
<?php include ("connexion_bdd.php");

// ### Verification de l'identifiant:
if (isset($_GET['id']) and is_numeric($_GET['id'])){
    // ## Récupère le premier enregistement
    $id = htmlentities($_GET['id']);
    $ma_requete_SQL ="SELECT ad.idAdherent, ad.nomAdherent, ad.adresse, ad.datePaiement
                        FROM ADHERENT ad
                        WHERE ad.idAdherent=".$id.";";

    $reponse = $bdd->query($ma_requete_SQL);
    $donnees = $reponse->fetch();
}

if (isset($_POST['idAdherent']) and isset($_POST['nomAdherent']) and isset($_POST['adresse']) and isset($_POST['datePaiement'])){

    $donnees['idAdherent'] = $_POST['idAdherent'];
    $donnees['nomAdherent'] = htmlentities($_POST['nomAdherent']);
    $donnees['adresse'] = htmlentities($_POST['adresse']);

    if (! preg_match("/^[A-Za-z]{2,}/", $donnees['nomAdherent']))
        $erreurs['nomAdherent'] = 'Nom composé de 2 lettres minimum';
    if (! preg_match("/^[A-Za-z]{2,}/", $donnees['adresse']))
        $erreurs['adresse'] = 'Adresse composée de 2 lettres minimum';
    if (! preg_match("#^([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})$#", $donnees['datePaiement'], $matches))
        $erreurs['datePaiement'] = 'La date doit être au format AAAA-MM-JJ';
    else {
        //vardump($matches);
        if (checkdate($matches[1], $matches[2], $matches[3])) {
            $erreurs['datePaiement'] = 'Date invalide';
        }
        else $donnees['datePaiement_us'] = $matches[3] . "-".$matches[2] . "-" . $matches[1];
    }

    if (empty($erreurs)) {
        // ## Accès au modèle:
        $ma_requete_SQL = "UPDATE ADHERENT SET nomAdherent='" . $donnees['nomAdherent'] . "',
        adresse='" . $donnees['adresse'] . "',
        datePaiement='" . $donnees['datePaiement'] . "'
        WHERE idAdherent =" . $donnees['idAdherent'] . ";";
        // var_dump($ma_requete_SQL);
        $bdd->exec($ma_requete_SQL);
        // ## redirection
        header("Location: Adherent_show.php");
    }
}
?>

<form method="post" action="Adherent_edit.php">
    <div class="row">
        <fieldset>
            <legend>Modifier cet adhérent</legend>
            <!-- ## Pour conserver la valeur de l'id -->
            <input name="idAdherent" type="hidden" value="<?php if (isset($donnees['idAdherent'])) echo $donnees['idAdherent'] ?>">

            <label>Nom de l'adhérent</label>
            <input type="text" name="nomAdherent" size="18" value="<?php if (isset($donnees['nomAdherent'])) echo $donnees['nomAdherent'] ?>" >
            <?php if (isset($erreurs['nomAdherent']))
                echo '<div class="alertdanger">'.$erreurs['nomAdherent'].'</div>';
            ?>
            <label>Adresse de l'adhérent</label>
            <input type="text" name="adresse" size="18" value="<?php if (isset($donnees['adresse'])) echo $donnees['adresse'] ?>" >
            <?php if (isset($erreurs['adresse']))
                echo '<div class="alertdanger">'.$erreurs['adresse'].'</div>';
            ?>

            <label>Date de paiement</label>
            <input type="date" name="datePaiement" placeholder="aaaa/mm/jj" value="<?php if (isset($donnees['datePaiement'])) {echo $donnees['datePaiement']; }else {date('Y-m-d');}?>">
            <?php if (isset($erreurs['datePaiement']))
                echo '<div class="alertdanger">'.$erreurs['datePaiement'].'</div>';
            ?>

            <input type="submit" name="ModifierAdherent" value="Modifier" >
        </fieldset>
    </div>
</form>
