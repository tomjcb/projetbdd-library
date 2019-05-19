<?php include ("connexion_bdd.php");


if(isset($_POST['idAdherent']) AND isset($_POST['noExemplaire']) AND isset($_POST['dateEmprunt']))
{
    $erreurs=array();
    $donnees['idAdherent']=htmlentities($_POST['idAdherent']);
    $donnees['noExemplaire']=htmlentities($_POST['noExemplaire']);
    $donnees['dateEmprunt']=htmlentities($_POST['dateEmprunt']);

    if (!preg_match("/[1-9]{1,}/", $donnees['idAdherent'])) {
        $erreurs['idAdherent'] = "L\'ID de l'adhérent est invalide";
    }

    if (!preg_match("/[1-9]{1,}/", $donnees['noExemplaire'])) {
        $erreurs['noExemplaire'] = "L'exemplaire selectionné ne peut pas être emprunté";
    }

    if (!preg_match("#^([0-9]{1,2})-([0-9]{1,2})-([0-9]{4})$#", $donnees['dateEmprunt'], $matches))
        $erreurs['dateEmprunt']='La date d\'emprunt doit être au format JJ/MM/AAAA';

    else if (!checkdate($matches[2], $matches[1], $matches[3])) $erreurs['dateEmprunt']="La date n'est pas valide";

    else $donnees['dateEmprunt_us']=$matches[3]."-".$matches[2]."-".$matches[1];

    if (empty($erreurs)) {
        $ma_requete_SQL = "INSERT INTO EMPRUNT (idAdherent,noExemplaire,dateEmprunt) VALUES ('".$donnees['idAdherent']."','".$donnees['noExemplaire']."','".$donnees['dateEmprunt_us']."');";
        $bdd->exec($ma_requete_SQL);
        header("Location: Emprunt_show.php");
    }
}


$selec = false;

$ma_requete_sql1 = "SELECT ADHERENT.nomAdherent, ADHERENT.idAdherent FROM ADHERENT ORDER BY nomAdherent;";
$reponse1 = $bdd->query($ma_requete_sql1);
$donnees1 = $reponse1 ->fetchAll();

$ma_requete_sql2 = "SELECT OEUVRE.titre, OEUVRE.noOeuvre
                    FROM OEUVRE
                    JOIN EXEMPLAIRE ON EXEMPLAIRE.noOeuvre = OEUVRE.noOeuvre
                    HAVING COUNT(EXEMPLAIRE.noOeuvre) > 0;";
$reponse2 = $bdd->query($ma_requete_sql2);
$donnees2 = $reponse2 ->fetchAll();


if (isset($_POST['idAdherent'])){
    $idAdherent = $_POST['idAdherent'];
    $selec = true;

    $ma_requete_sql4 ="SELECT nomAdherent FROM adherent where idAdherent = ".$idAdherent.";";
    $reponse4 = $bdd->query($ma_requete_sql4);
    $donnees4 = $reponse4->fetchAll();

    $ma_requete_sql3 ="SELECT E2.noExemplaire, EXEMPLAIRE.noExemplaire AS noExemplaireExistant, E2.etat, OEUVRE.titre, OEUVRE.noOeuvre
    FROM EXEMPLAIRE
    INNER JOIN OEUVRE
    ON EXEMPLAIRE.noOeuvre = OEUVRE.noOeuvre
    LEFT JOIN EXEMPLAIRE E2
    ON E2.noExemplaire = EXEMPLAIRE.noExemplaire
    AND E2.noExemplaire NOT IN (SELECT EMPRUNT.noExemplaire FROM EMPRUNT WHERE EMPRUNT.dateRendu IS NULL OR EMPRUNT.dateRendu = '0000-00-00')
    WHERE E2.noExemplaire IS NOT NULL
    ORDER BY OEUVRE.titre ASC, E2.noExemplaire ASC;";
    $reponse3 = $bdd->query($ma_requete_sql3);
    $donnees3 = $reponse3->fetchAll();

    $today = getdate();
}



?>

<?php include("v_head.php");  ?>
<?php include ("v_nav.php");  ?>

<div class="row">
    <div class="title">Ajouter un emprunt</div>
</div>
<div class="row">
    <div class="container">
        <?php if($selec == false){ ?>
        <form class="col-12" action="Emprunt_add.php" method="post">
            <div class="form-group col-12" id="idAdherent">
                <label>Nom de l'adhérent :</label>
                <select name="idAdherent" class="form-control">
                    <?php
                        foreach($donnees1 as $values){ ?>
                            <option value="<?php echo $values['idAdherent'] ?>"
                                <?php if(isset($donnees['idAdherent']) and $donnees['idAdherent'] == $adherent['idAdherent']) echo "selected"; ?>
                            ><?php echo $values['nomAdherent'];?></option>
                        <?php }
                    ?>
                </select>
                <div class="scnd">
                    <input class="btn btn-lg btn-primary" type="submit" name="valider" value="Valider" >
                </div>
            </div>
        </form>
    <?php }
    if ($selec == true){
    ?>
        <div class="row">
            <div class="title-2">
                <?php foreach ($donnees4 as $value){?>
                    Adhérent : <?php echo $value['nomAdherent'] ; } ?>
            </div>
        </div>
        <div class="row">
            <div class="container scnd">
                <a class="btn btn-lg btn-primary" href="Emprunt_add.php"> Changer d'adhérent </a>
            </div>
        </div>
        <form class="col-12" action="Emprunt_add.php" method="post">
            <div class="form-group" id="noExemplaire">
                <label>Exemplaire d'une oeuvre : </label>
                <select name="noExemplaire" class="form-control">
                    <?php
                        foreach ($donnees3 as $value){ ?>
                            <option value="<?php echo $value['noExemplaire'];?>"
                                <?php if(isset($donnees['noExemplaire']) and $donnees['noExemplaire'] == $exemplaire['noExemplaire']) echo "selected"; ?>
                            ><?php echo $value['noExemplaire']." - ".$value['titre'];?></option>
                        <?php }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label>Date de l'emprunt</label>
                <?php
                if (isset($donnees['dateEmprunt'])) echo '<input  class="form-control" name="dateEmprunt" type="text" size="18" value="'.date("d/m/Y", strtotime($value['dateEmprunt'])).'"/>';
                else echo '<input  class="form-control" name="dateEmprunt" type="text" size="18" value="'.$today['mday'].'-'.$today['mon'].'-'.$today['year'].'"/>';
                ?>
            </div>

            <input type="hidden" name="idAdherent" value="<?php echo $idAdherent ?>">

            <button class="btn btn-lg btn-primary btn-block" type="submit" name="valider">Ajouter</button>
        </form>
        <?php }?>
    </div>
</div>

<?php include ("v_foot.php"); ?>





