<?php include("v_head.php");  ?>
<?php include ("v_nav.php");  ?>
<?php include ("connexion_bdd.php");

$ma_requete_sql = "SELECT AUTEUR.nomAuteur, AUTEUR.idAuteur FROM AUTEUR;";
$reponse = $bdd->query($ma_requete_sql);
$data = $reponse ->fetchAll();

//traitement
if(isset($_POST["titre"]) AND isset($_POST["dateParution"]) AND isset($_POST['auteurs']))
{
    // ## contrôles des données
    $donnees['titre']= $_POST['titre'];
    $donnees['dateParution']=htmlentities($_POST['dateParution']);
    $donnees['idAuteur']=htmlentities($_POST['auteurs']);

    if (! preg_match("/^[A-Za-z]{2,}/", $donnees['titre']))
        $erreurs['titre'] = 'Titre : au moins de deux caractères';

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
    // ## accès au modèle
    if (empty($erreurs)) {
        $ma_requete_SQL = "INSERT INTO OEUVRE (noOeuvre, titre, dateParution, idauteur) VALUES
        (NULL, '" . $donnees['titre'] . "','" . $donnees['dateParution'] . "', " . $donnees['idAuteur'] . ");";
        $bdd->exec($ma_requete_SQL);
        // ## Redirection
        header("Location: Oeuvre_show.php");
    }
}
?>

<div class="contenu">

<div class="row">
    <div class="title">Ajouter une oeuvre</div>
</div>

<form method="post" action="Oeuvre_add.php">
    <div class="row">
        <fieldset class="element-center">

            <div class="col-md-2 offset-md-5">
                <label>Titre</label>
                <br>
                <input name="titre" class="form-control" type="text" size="18" value=""/>
                <?php if (isset($erreurs['titre']))
                    echo '<br><div class="alert alert-danger">'.$erreurs['titre'].'</div>';
                ?>
            </div>

            <br><br>

            <div class="col-md-2 offset-md-5">
                <label>Date de parution</label>
                <br>
                <input name="dateParution" class="form-control" type="text" size="18" value="<?php if (isset($donnees['dateParution'])) echo $donnees['dateParution'] ?>"/>
                <?php if (isset($erreurs['dateParution']))
                    echo '<br><div class="alert alert-danger">'.$erreurs['dateParution'].'</div>';
                ?>
            </div>

            <br><br>

            <div class="col-md-2 offset-md-5">
                <label>Auteur</label>
                <br>
                <?php $i = 1;?>
                <select class="browser-default custom-select" name="auteurs">
                    <?php
                    if(isset($data[0])){
                        //for ($i = 0; $i < count($data); $i = $i+1){
                        foreach($data as $values){ ?>
                            <option value="<?php echo $values['idAuteur'] ?>"  ><?php echo $values['nomAuteur'];?></option>
                        <?php }
                    }
                    ?>
                </select>
            </div>

            <br><br>

            <input type="submit" class="btn btn-info" name="AddOeuvre" value="Ajouter une oeuvre"/>
        </fieldset>
    </div>
</form>

</div>

<?php include ('v_foot.php'); ?>