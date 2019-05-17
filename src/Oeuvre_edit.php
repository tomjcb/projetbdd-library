<?php include("v_head.php"); ?>
<?php include ('v_nav.php'); ?>
<?php include ('connexion_bdd.php');

if(isset($_GET["id"]) AND is_numeric($_GET["id"])){
    $id = htmlentities($_GET['id']);
    $ma_requete_sql = "SELECT oe.titre, oe.noOeuvre, oe.idAuteur, oe.dateParution
    FROM OEUVRE oe
    WHERE noOeuvre=".$id.";";
    $reponse = $bdd->query($ma_requete_sql);
    $donnees = $reponse ->fetch();
}
$ma_requete_sql = "SELECT AUTEUR.nomAuteur, AUTEUR.idAuteur FROM AUTEUR;";
$reponse = $bdd->query($ma_requete_sql);
$data = $reponse ->fetchAll();

if (isset($_POST['titre']) and isset($_POST['dateParution']) and isset($_POST['auteurs']) and isset($_POST['noOeuvre'])){

    $donnees['titre'] = $_POST['titre'];
    $donnees['dateParution'] = htmlentities($_POST['dateParution']);
    $donnees['idAuteur'] = htmlentities($_POST['auteurs']);
    $donnees['noOeuvre'] = htmlentities($_POST['noOeuvre']);

    if (! preg_match("/^[A-Za-z]{2,}/", $donnees['titre'])) {
        $erreurs['titre'] = 'Titre : au moins de deux caractères';
    }
    if (empty($donnees['dateParution'])){ //Check les erreurs de date
        $erreurs['dateParution'] = 'La date doit être non vide';
    }
    else {
        //On scinde la chaîne de cara en 3 éléments stockés dans un Array
        $time=explode("-", $donnees['dateParution']);
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
            $erreurs['dateParution'] = 'Date invalide';
        }

    }



    if (empty($erreurs)) {
        // ## Accès au modèle:
        $ma_requete_SQL = "UPDATE OEUVRE SET titre='" . $donnees['titre'] . "',
        dateParution='" . $donnees['dateParution'] . "',
        idAuteur='" . $donnees['idAuteur'] . "'
        WHERE noOeuvre =" . $donnees['noOeuvre'] . ";";
        // var_dump($ma_requete_SQL);
        $bdd->exec($ma_requete_SQL);
        // ## redirection
        header("Location: Oeuvre_show.php");
    }
}
?>


<div class="row">
    <div class="title">Modifier une oeuvre</div>
</div>

<form method="post" action="Oeuvre_edit.php">
    <div class="row">
        <fieldset class="element-center">
            <!-- ## Pour conserver la valeur de l'id -->
            <input name="noOeuvre" type="hidden" value="<?php if (isset($donnees['noOeuvre'])) echo $donnees['noOeuvre'] ?>">

            <div class="col-md-2 offset-md-5">
                <label>Titre</label>
                <br>
                <input type="text" class="form-control" name="titre" size="18" value="<?php if (isset($donnees['titre'])) echo $donnees['titre'] ?>" >
                <?php if (isset($erreurs['titre']))
                    echo '<br><div class="alert alert-danger">'.$erreurs['titre'].'</div>';
                ?>
            </div>

            <br><br>

            <div class="col-md-2 offset-md-5">
                <label>Date de parution</label>
                <br>
                <input type="text" class="form-control" name="dateParution" size="18" value="<?php if (isset($donnees['dateParution'])) echo $donnees['dateParution'] ?>" >
                <?php if (isset($erreurs['dateParution']))
                    echo '<br><div class="alert alert-danger">'.$erreurs['dateParution'].'</div>';
                ?>
            </div>

            <br><br>

            <div class="col-md-2 offset-md-5">
                <label>Auteur</label>
                <br>
                <select class="browser-default custom-select" name="auteurs">
                    <?php
                    if(isset($data[0])){
                    foreach($data as $values){ ?>
                        <option value="<?php echo $values['idAuteur'] ?>" <?php if($donnees['idAuteur'] == $values['idAuteur']){echo "selected";} ?> ><?php echo $values['nomAuteur'];?></option>
                        <?php }
                    }
                    ?>
                </select>
            </div>

            <br><br>
            <input type="submit" class="btn btn-info" name="ModifierOeuvre" value="Modifier" >
        </fieldset>
    </div>
</form>

<?php include ('v_foot.php'); ?>