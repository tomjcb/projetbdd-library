<?php include("v_head.php");  ?>
<?php include ("v_nav.php");  ?>
<?php include ("connexion_bdd.php");

$selec=false;

$ma_requete_sql1 = "SELECT ADHERENT.nomAdherent, ADHERENT.idAdherent FROM ADHERENT ORDER BY nomAdherent;";
$reponse1 = $bdd->query($ma_requete_sql1);
$donnees1 = $reponse1 ->fetchAll();


if (isset($_POST['idAdherent'])){
    $donnees['idAdherent']=htmlentities($_POST['idAdherent']);
    $idAdherent = $_POST['idAdherent'];
    $selec = true;

    $ma_requete_sql2 ="SELECT nomAdherent FROM adherent where idAdherent = ".$idAdherent.";";
    $reponse2 = $bdd->query($ma_requete_sql2);
    $donnees2 = $reponse2->fetchAll();

    $ma_requete_sql3 = "SELECT OEUVRE.titre, EXEMPLAIRE.noExemplaire, EMPRUNT.dateEmprunt,
                    DATEDIFF(curdate(),dateEmprunt) as nbJours,
                    curdate() as dateJour
                    FROM ADHERENT
                    JOIN EMPRUNT ON EMPRUNT.idAdherent=ADHERENT.idAdherent
                    JOIN EXEMPLAIRE ON EMPRUNT.noExemplaire = EXEMPLAIRE.noExemplaire
                    JOIN OEUVRE ON EXEMPLAIRE.noOeuvre = OEUVRE.noOeuvre
                    WHERE ADHERENT.idAdherent = ".$idAdherent." AND EMPRUNT.dateRendu IS NULL;";
    $reponse3 = $bdd->query($ma_requete_sql3);
    $donnees3 = $reponse3->fetchAll();

    $today = getdate();
}


if (isset($_POST['idAdherent']) AND isset($_POST['noExemplaire']) ) {

    $donnees['idAdherent']=htmlentities($_POST['idAdherent']);
    $donnees['noExemplaire']=htmlentities($_POST['noExemplaire']);

    $ma_requete_SQL2 = "UPDATE EMPRUNT SET 
                   dateRendu = ". $today ."
                   where noExemplaire = " . $donnees['noExemplaire'] . ";";
    $bdd->exec($ma_requete_SQL2);

    header("Location: Emprunt_return.php?idAdherent=".$donnees['idAdherent']);
}

?>
<div class="row">
    <div class="title">Rendre un exemplaire</div>
</div>

<div class="row">
    <div class="container">
        <?php if($selec == false){ ?>
        <form class="col-12" action="Emprunt_return.php" method="post">
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
                <input class="btn btn-lg btn-primary" type="submit" name="valider" value="Valider" >
            </div>
        </form>
        <?php }
        if ($selec == true){
        ?>
            <div class="row">
                <div class="title-2">
                    <?php foreach ($donnees2 as $value){?>
                    Adhérent : <?php echo $value['nomAdherent'] ; } ?>
                </div>
            </div>
            <div class="row">
                <table class="table table-bordered table-hover">
                    <caption> Récapitulatif des Emprunts </caption>
                    <?php if(isset($donnees3[0])){ ?>
                    <thead class="table-head">
                    <tr>
                        <th>Titre de l'oeuvre</th>
                        <th>Date emprunt</th>
                        <th>Nombre de jours</th>
                        <th>N° Exemplaire</th>
                        <th>Date rendu</th>
                        <th>Rendre</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($donnees3 as $value) { ?>
                        <tr>
                            <td>
                                <?php echo($value['titre']); ?>
                            </td>
                            <td>
                                <?php echo date("d/m/Y", strtotime($value['dateEmprunt'])); ?>
                            </td>
                            <td>
                                <?php echo($value['nbJours']) ?>
                            </td>
                            <td>
                                <?php echo($value['noExemplaire']); ?>
                            </td>
                            <td>
                                <?php echo date("d/m/Y", strtotime($value['dateJour']));?>
                            </td>
                            <td>
                                <a class="btn btn-secondary" role="button" href="Emprunt_show.php">Rendre</a>
                            </td>
                        </tr>
                        <?php }
                        } else{ ?>
                        <tr>
                            <td>
                                Pas d'emprunt en cours
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php }?>
    </div>
</div>


<?php include ("v_foot.php");?>
