

<?php
include("v_head.php");
include("v_nav.php");

include('connexion_bdd.php');

$erreurs = array();

if (isset($_GET["id"]) AND is_numeric($_GET["id"])){
    $id = htmlentities($_GET['id']);
    $ma_requete_SQL="SELECT OEUVRE.titre, EXEMPLAIRE.noExemplaire, EXEMPLAIRE.etat, EXEMPLAIRE.dateAchat, EXEMPLAIRE.prix, OEUVRE.noOeuvre, AUTEUR.nomAuteur, AUTEUR.prenomAuteur
                    FROM OEUVRE
                    INNER JOIN AUTEUR ON AUTEUR.idAuteur = OEUVRE.idAuteur
                    INNER JOIN EXEMPLAIRE ON EXEMPLAIRE.noOeuvre = OEUVRE.noOeuvre
                    WHERE EXEMPLAIRE.noExemplaire = ".$id.";";
    $reponse = $bdd->query($ma_requete_SQL);
    $donnees = $reponse->fetch();
    echo $donnees['noOeuvre'];
}

// traitement
if( isset($_POST['etat']) AND isset($_POST['dateAchat']) AND isset($_POST['prix']))
{
    $donnees['etat'] = htmlentities($_POST['etat']);
    $donnees['dateAchat'] = htmlentities($_POST['dateAchat']);
    $donnees['prix'] = htmlentities($_POST['prix']);
    // $donnees['noOeuvre'] = htmlentities($_POST['titre']);


    if (empty($donnees['dateAchat'])){ //Check les erreurs de date
        $erreurs['dateAchat'] = 'La date doit être non vide';
    }
    else {
        //On scinde la chaîne de cara en 3 éléments stockés dans un Array
        $time=explode("-", $donnees['dateAchat']);
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
            $erreurs['dateAchat'] = 'Date invalide';
        }

    }

    if (! preg_match("/^[1-9][0-9]{0,15}/", $donnees['prix'])) {
        $erreurs['prix'] = "Prix doit être composé de 1 chiffre minimum";
        echo $erreurs['prix'];
    }

    if (empty($erreurs)) {

        $ma_requete_SQL = "UPDATE EXEMPLAIRE SET
                        etat='".$donnees['etat']."', dateAchat='".$donnees['dateAchat']."',
                        prix=".$donnees['prix'].", noOeuvre=".$donnees['noOeuvre']."
                        WHERE noExemplaire =".$donnees['noExemplaire'].";";
        $bdd->exec($ma_requete_SQL);

        $id = $donnees['noOeuvre'];

        // redirection
        header("Location: Exemplaire_show.php?id=$id");
    }

}

// affichage de la vue
?>

<div class="row">
    <div class="title">Modifier un exemplaire</div>
</div>

<form method="post" action="Exemplaire_edit.php?id=<?php echo $id; ?>">
    <div class="row">
        <fieldset class="element-center">
            <div class="col-md-2 offset-md-5">
                <div class="title_oeuvre">
                    Titre : <?php echo $donnees['titre'] ; ?> de <?php echo $donnees['prenomAuteur']." ".$donnees['nomAuteur']; ?> <br> <br>
                </div>
            </div>

            <div class="col-md-4 offset-md-4">
                <label> Etat :</label>
                <br>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" class="custom-control-input" name="etat" value="NEUF" id="NEUF" <?php if(isset($donnees['etat']) and $donnees['etat'] == "NEUF") echo "checked"; ?>>
                    <label class="custom-control-label" for="NEUF">Neuf</label>
                </div>

                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" class="custom-control-input" name="etat" value="BON" id="BON" <?php if(isset($donnees['etat']) and $donnees['etat'] == "BON") echo "checked"; ?>>
                    <label class="custom-control-label" for="BON">Bon</label>
                </div>

                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" class="custom-control-input" name="etat" value="MOYEN" id="MOYEN" <?php if(isset($donnees['etat']) and $donnees['etat'] == "MOYEN") echo "checked"; ?>>
                    <label class="custom-control-label" for="MOYEN">Moyen</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" class="custom-control-input" name="etat" value="MAUVAIS" id="MAUVAIS" <?php if(isset($donnees['etat']) and $donnees['etat'] == "MAUVAIS") echo "checked"; ?>>
                    <label class="custom-control-label" for="MAUVAIS">Mauvais</label>
                </div>
            </div>

            <br><br>

            <div class="col-md-2 offset-md-5">
                <label>Date d'Achat</label>
                <br>
                <input name="dateAchat" class="form-control" type="text" size="18" value="<?php if(isset($donnees['dateAchat'])) echo $donnees['dateAchat']; ?>"/>
                <?php if(isset($erreurs['dateAchat'])) echo '<br><div class="alert alert-danger">'.$erreurs['dateAchat'].'</div>'; ?>
            </div>

            <br><br>

            <div class="col-md-2 offset-md-5">
                <label>Prix</label>
                <br>
                <input name="prix" class="form-control" type="text" size="18" value="<?php if(isset($donnees['prix'])) echo $donnees['prix']; ?>"/>
                <?php if(isset($erreurs['prix'])) echo '<br><div class="alert alert-danger">'.$erreurs['prix'].'</div>'; ?>
            </div>

            <br>
            <input type="submit" class="btn btn-info" name="AddExemplaire" value="Modifier l'exemplaire" />
        </fieldset>
    </div>
</form>
<?php include("v_foot.php.php");?>