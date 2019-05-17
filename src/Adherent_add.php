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
    if (empty($donnees['datePaiement'])){ //Check les erreurs de date
        $erreurs['datePaiement'] = 'La date doit être non vide';
    }
    else {
        //On scinde la chaîne de cara en 3 éléments stockés dans un Array
        $time=explode("-", $donnees['datePaiement']);
        $year= (int)$time[0];

        if(isset($time[1])){//Si la date a bien pu être découpée, on stocke la valeur
            $month= (int)$time[1];
        }
        else{//Sinon on donne une valeur trop haute pour provoquer une erreur
            $month = 99;
        }

        if(isset($time[2])){//Pareil
            $day = (int)$time[2];
        }
        else{
            $day = 99;
        }

        if (!checkdate($month, $day, $year)) {//On regarde si la date est correcte
            $erreurs['datePaiement'] = 'Date invalide';
        }

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

<div class="row">
    <div class="title">Ajouter un adhérent</div>
</div>

<form method="post" action="Adherent_add.php">
    <div class="row">
        <fieldset class="element-center">

            <div class="col-md-2 offset-md-5">
                <label>Nom de l'Adherent</label>
                <br>
                <input name="nomAdherent" class="form-control" type="text" size="18" value="<?php if (isset($donnees['nomAdherent'])) echo $donnees['nomAdherent']?>">
                <?php if (isset($erreurs['nomAdherent']))
                    echo '<br><div class="alert alert-danger">'.$erreurs['nomAdherent'].'</div>';
                ?>
            </div>

            <br><br>

            <div class="col-md-2 offset-md-5">
                <label>Adresse</label>
                <br>
                <input name="adresseAdherent" class="form-control" type="text" size="18" value="<?php if (isset($donnees['adresseAdherent'])) echo $donnees['adresseAdherent']?>">
                <?php if (isset($erreurs['adresseAdherent']))
                    echo '<br><div class="alert alert-danger">'.$erreurs['adresseAdherent'].'</div>';
                ?>
            </div>

            <br><br>

            <div class="col-md-2 offset-md-5">
                <label>Date de paiement</label>
                <br>
                <input type="text" class="form-control" name="datePaiement" placeholder="aaaa-mm-jj" value="<?php if (isset($donnees['datePaiement'])) {echo $donnees['datePaiement']; }else {date('Y-m-d');}?>">
                <?php if (isset($erreurs['datePaiement']))
                    echo '<br><div class="alert alert-danger">'.$erreurs['datePaiement'].'</div>';
                ?>
            </div>

            <br><br>

            <input name="addAdherent" class="btn btn-info" type="submit" value="Ajouter un adhérent">
        </fieldset>
    </div>
</form>

<?php include ('v_foot.php'); ?>