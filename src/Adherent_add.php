<?php include("v_head.php");  ?>
<?php include ("v_nav.php");  ?>
<?php include ("connexion_bdd.php");

if (isset($_POST["nomAdherent"]) AND isset($_POST["adresseAdherent"]) AND isset($_POST["datePaiement"])){

    // ## Controles des données
    $donnees['nomAdherent']= $_POST["nomAdherent"];
    $donnees['adresseAdherent']= htmlentities($_POST["adresseAdherent"]);
    $donnees['datePaiement']= htmlentities($_POST["datePaiement"]);

    if (! preg_match("/^[A-Za-z]{2,}/", $donnees['nomAdherent']))
        $erreurs['nomAdherent'] = 'Nom composé de 2 lettres minimum';
    if (! preg_match("/^[A-Za-z]{2,}/", $donnees['adresseAdherent']))
        $erreurs['adresseAdherent'] = 'Adresse au moins de deux caractères';
    if (! preg_match("#^([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})$#", $donnees['datePaiement'], $matches))
        $erreurs['datePaiement'] = 'La date doit être au format AAAA-MM-JJ';
    else {
        //vardump($matches);
        if (checkdate($matches[1], $matches[2], $matches[3])) {
            $erreurs['datePaiement'] = 'Date invalide';
        }
        else $donnees['datePaiement_us'] = $matches[3] . "-".$matches[2] . "-" . $matches[1];
    }

    if (empty($erreurs)){
        // ## accès au modèle
        $ma_requete_SQL="INSERT INTO ADHERENT (idAdherent, nomAdherent, adresse, datePaiement)
                      VALUES (NULL, '".$donnees['nomAdherent']."','".$donnees['adresseAdherent']."','".$donnees['datePaiement']."');";
        $bdd->exec($ma_requete_SQL);

        // ## Redirection
        header("Location: Adherent_show.php");
    }

}
?>

<body>
<form method="post" action="Adherent_add.php">
    <div class="row">
        <fieldset>
            <legend>Ajouter un adhérent</legend>
            <label>Nom de l'Adherent</label>
            <input name="nomAdherent" type="text" size="18" value="<?php if (isset($donnees['nomAdherent'])) echo $donnees['nomAdherent']?>">
            <?php if (isset($erreurs['nomAdherent']))
                echo '<div class="alertdanger">'.$erreurs['nomAdherent'].'</div>';
            ?>

            <label>Adresse</label>
            <input name="adresseAdherent" type="text" size="18" value="<?php if (isset($donnees['adresseAdherent'])) echo $donnees['adresseAdherent']?>">
            <?php if (isset($erreurs['adresseAdherent']))
                echo '<div class="alertdanger">'.$erreurs['adresseAdherent'].'</div>';
            ?>

            <label>Date de paiement</label>
            <input type="text" name="datePaiement" placeholder="aaaa-mm-jj" value="<?php if (isset($donnees['datePaiement'])) {echo $donnees['datePaiement']; }else {date('Y-m-d');}?>">
            <?php if (isset($erreurs['datePaiement']))
                echo '<div class="alertdanger">'.$erreurs['datePaiement'].'</div>';
            ?>

            <input name="addAdherent" type="submit" value="Ajouter un adhérent">
        </fieldset>
    </div>
</form>
</body>
