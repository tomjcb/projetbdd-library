<?php include("v_head.php");  ?>
<?php include ("v_nav.php");  ?>

<div class="row">
    <div class="title">Gestion des emprunts</div>
</div>


<?php

include("connexion_bdd.php");

$selec = false;
$adherent="";

$requete ="SELECT * , 
            DATEDIFF(curdate(), DATE_ADD(EMPRUNT.dateEmprunt, INTERVAL 90 DAY)) AS RETARD,
            DATEDIFF(EMPRUNT.dateRendu, EMPRUNT.dateEmprunt) AS diffEmprRendu,
            DATEDIFF(curdate(),dateEmprunt) as nbJoursEmprunt
            FROM adherent
            JOIN emprunt ON adherent.idAdherent=emprunt.idAdherent
            JOIN exemplaire ON emprunt.noExemplaire = exemplaire.noExemplaire
            JOIN oeuvre ON exemplaire.noOeuvre = oeuvre.noOeuvre
            ORDER BY adherent.nomAdherent;";
$reponse = $bdd->query($requete);
$donnees = $reponse->fetchAll();

$ma_requete_sql1 = "SELECT ADHERENT.idAdherent, ADHERENT.nomAdherent FROM ADHERENT ORDER BY nomAdherent;";
$reponse1 = $bdd->query($ma_requete_sql1);
$donnees1 = $reponse1 ->fetchAll();

if (isset($_POST['idAdherent'])){
    $adherent =$_POST['idAdherent'];
    header('Location: Emprunt_delete.php?idAdherent='.$adherent);
}

?>

<div class="row">
    <div class="container">
    <form class="col-12" action="Emprunt_show.php" method="post">
        <div class="form-group" id="idAdherent">
            <label>Choisir un adhérent en particulier :</label>
            <select name="idAdherent" class="form-control">
                <?php
                foreach($donnees1 as $values){ ?>
                    <option value="<?php echo $values['idAdherent'] ?>"
                        <?php if(isset($donnees1['idAdherent'])){ echo "selected";} ?>
                    ><?php echo $values['nomAdherent'];?></option>
                <?php }
                ?>
            </select>
        </div>
        <div class="scnd">
            <input class="btn btn-lg btn-primary" type="submit" name="valider" value="Valider" >
        </div>
    </form>
    </div>

</div>

<!--         <a href="Emprunt_delete.php?idAdherent=<?= $value['idAdherent'] ?>&noExemplaire=<?= $value['noExemplaire']?>&dateEmprunt=<?= $value['dateEmprunt']?>&dateRendu=<?= $value['dateRendu']?>" class="btn btn-lg btn-primary" role="button">Valider</a>
-->

<div class="row">
    <div class="container">
        <div class="title-2">
            Aperçu de tous les emprunts
        </div>
        <div class="table-responsive-sm">
            <table class="table table-bordered table-hover">
                <?php if (isset($donnees[0])): ?>
                <thead class="table-head">
                <tr>
                    <th>Nom adherent</th>
                    <th>Titre</th>
                    <th>Date d'emprunt</th>
                    <th>Date de rendu</th>
                    <th>N° exemplaire</th>
                    <th>Durée d'emprunt</th>
                    <th>Retard</th>
                    <th>Opération</th>
                </tr>
                </thead>
                <?php foreach ($donnees as $value ): ?>
                    <tr>
                        <td>
                            <?php echo $value['nomAdherent']; ?>
                        </td>
                        <td>
                            <?php echo $value['titre']; ?>
                        </td>
                        <td>
                            <?php echo date("d/m/Y", strtotime($value['dateEmprunt'])); ?>
                        </td>
                        <td>
                            <?php
                            if ($value['dateRendu'] != "") {
                                echo date("d/m/Y", strtotime($value['dateRendu']));
                            }
                            else {
                                echo "";
                            }?>

                        </td>
                        <td>
                            <?php echo $value['noExemplaire']; ?>
                        </td>
                        <td>
                            <?php
                            if ($value['dateRendu'] != "") {
                                echo $value['diffEmprRendu'];
                            }
                            else {
                                echo $value['nbJoursEmprunt'];
                            }
                            ?>
                        </td>
                        <td class="retard">
                            <?php
                            if ($value['dateRendu'] == ""){
                                if ($value['RETARD'] > 0) {
                                    echo $value['RETARD'];
                                }
                            }
                            ?>
                        </td>
                        <td>
                            <a class="btn btn-danger" href="Emprunt_delete.php?idAdherent=<?php echo $value['idAdherent'];?>&noExemplaire=<?php echo $value['noExemplaire'];?>&dateEmprunt=<?php echo $value['dateEmprunt'];?>&dateRendu=<?php echo $value['dateRendu'];?>"><i class="fa fa-trash"></i></a>
                            <a class="btn btn-info" href="Emprunt_edit.php?id=<?php echo $value['noExemplaire']; ?>"><i class="fa fa-wrench"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td>Pas d'enregistrements</td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>
    </div>
</div>


<?php include ("v_foot.php");  ?>
