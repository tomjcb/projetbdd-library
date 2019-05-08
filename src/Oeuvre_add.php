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
    // ## accès au modèle
    $ma_requete_SQL = "INSERT INTO OEUVRE (noOeuvre, titre, dateParution, idauteur) VALUES
        (NULL, '".$donnees['titre']."','".$donnees['dateParution']."', ".$donnees['idAuteur'].");";
    $bdd->exec($ma_requete_SQL);
    // ## Redirection
    header("Location: Oeuvre_show.php");
}
?>


<form method="post" action="Oeuvre_add.php">
    <div class="row">
        <fieldset>
            <legend>Ajouter une Oeuvre</legend>
            <label> Titre
                <input name="titre" type="text" size="18" value=""/>
            </label>
            <label>Date de parution
                <input name="dateParution" type="date" size="18" value="2018-08-18"/>
            </label>
            <label>Auteur</label>
            <?php $i = 1;?>
            <select name="auteurs">
                <?php
                if(isset($data[0])){
                    //for ($i = 0; $i < count($data); $i = $i+1){
                    foreach($data as $values){ ?>
                        <option value="<?php echo $values['idAuteur'] ?>"  ><?php echo $values['nomAuteur'];?></option>
                    <?php }
                }
                ?>

            </select>

            <br><br>

            <input type="submit" name="AddOeuvre" value="Ajouter une oeuvre"/>
        </fieldset>
    </div>
</form>
