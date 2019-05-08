<?php
include ('connexion_bdd.php');

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

    if (! preg_match("/^[A-Za-z]{2,}/", $donnees['titre']))
        $erreurs['titre'] = 'Titre : au moins de deux caractères';
    if (! preg_match("#^([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})$#", $donnees['dateParution'], $matches))
        $erreurs['dateParution'] = 'La date doit être au format AAAA-MM-JJ';
    else {
        //vardump($matches);
        if (checkdate($matches[1], $matches[2], $matches[3])) {
            $erreurs['dateParution'] = 'Date invalide';
        }
        else $donnees['dateParution_us'] = $matches[3] . "-".$matches[2] . "-" . $matches[1];
    }

    /*if (!is_numeric($donnees['idAuteur']))
        $erreurs['idAuteur'] = 'Id auteur : chiffre ou nombre seulement';*/

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


<?php include("v_head.php"); ?>
<body>
<?php include ('v_nav.php'); ?>
<form method="post" action="Oeuvre_edit.php">
    <div class="row">
        <fieldset>
            <legend>Modifier une oeuvre</legend>
            <!-- ## Pour conserver la valeur de l'id -->
            <input name="noOeuvre" type="hidden" value="<?php if (isset($donnees['noOeuvre'])) echo $donnees['noOeuvre'] ?>">
            <label>Titre</label>
            <input type="text" name="titre" size="18" value="<?php if (isset($donnees['titre'])) echo $donnees['titre'] ?>" >
            <?php if (isset($erreurs['titre']))
                echo '<div class="alertdanger">'.$erreurs['titre'].'</div>';
            ?>

            <br><br>

            <label>Date de parution</label>
            <input type="date" name="dateParution" size="18" value="<?php if (isset($donnees['dateParution'])) echo $donnees['dateParution'] ?>" >
            <?php if (isset($erreurs['dateParution']))
                echo '<div class="alertdanger">'.$erreurs['dateParution'].'</div>';
            ?>

            <br><br>

            <label>Auteur</label>
            <select name="auteurs">
                <?php
                if(isset($data[0])){
                foreach($data as $values){ ?>
                    <option value="<?php echo $values['idAuteur'] ?>" <?php if($donnees['idAuteur'] == $values['idAuteur']){echo "selected";} ?> ><?php echo $values['nomAuteur'];?></option>
                    <?php }
                }
                ?>

            </select>

            <input type="submit" name="ModifierOeuvre" value="Modifier" >
        </fieldset>
    </div>
</form>
</body>