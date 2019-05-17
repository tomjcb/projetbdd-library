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
    $donnees['datePaiement'] = htmlentities($_POST['datePaiement']);

    if (! preg_match("/^[A-Za-z]{2,}/", $donnees['nomAdherent']))
        $erreurs['nomAdherent'] = 'Nom composé de 2 lettres minimum';
    if (! preg_match("/^[A-Za-z]{2,}/", $donnees['adresse']))
        $erreurs['adresse'] = 'Adresse composée de 2 lettres minimum';
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
<div class="row">
    <div class="title">Modifier un adhérent</div>
</div>

<form method="post" action="Adherent_edit.php">
    <div class="row">
        <fieldset class="element-center">
            <!-- ## Pour conserver la valeur de l'id -->
            <input name="idAdherent" type="hidden" value="<?php if (isset($donnees['idAdherent'])) echo $donnees['idAdherent'] ?>">

            <div class="col-md-2 offset-md-5">
                <label>Nom de l'adhérent</label>
                <br>
                <input type="text" class="form-control" name="nomAdherent" size="18" value="<?php if (isset($donnees['nomAdherent'])) echo $donnees['nomAdherent'] ?>" >
                <?php if (isset($erreurs['nomAdherent']))
                    echo '<br><div class="alert alert-danger">'.$erreurs['nomAdherent'].'</div>';
                ?>
            </div>

            <br><br>

            <div class="col-md-2 offset-md-5">
                <label>Adresse de l'adhérent</label>
                <br>
                <input type="text" class="form-control" name="adresse" size="18" value="<?php if (isset($donnees['adresse'])) echo $donnees['adresse'] ?>" >
                <?php if (isset($erreurs['adresse']))
                    echo '<br><div class="alert alert-danger">'.$erreurs['adresse'].'</div>';
                ?>
            </div>

            <br><br>

            <div class="col-md-2 offset-md-5">
                <label>Date de paiement</label>
                <input type="text" class="form-control" name="datePaiement" placeholder="aaaa/mm/jj" value="<?php if (isset($donnees['datePaiement'])) {echo $donnees['datePaiement']; }?>">
                <?php if (isset($erreurs['datePaiement']))
                    echo '<br><div class="alert alert-danger">'.$erreurs['datePaiement'].'</div>';
                ?>
            </div>

            <br><br>

            <input type="submit" class="btn btn-info" name="ModifierAdherent" value="Modifier" >
        </fieldset>
    </div>
</form>

<?php include ("v_foot.php");  ?>
