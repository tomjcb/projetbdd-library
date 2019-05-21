<?php include("v_head.php");  ?>
<?php include ("v_nav.php");  ?>
<?php include ("connexion_bdd.php");

$ma_requete_SQL = "SELECT ADHERENT.idAdherent, EXEMPLAIRE.noExemplaire, OEUVRE.titre, 
                    ADHERENT.nomAdherent, EMPRUNT.dateEmprunt, EMPRUNT.dateRendu
                    , DATEDIFF(curdate(),dateEmprunt) as nbJoursEmprunt
                    , DATEDIFF(curdate(),DATE_ADD(dateEmprunt, INTERVAL 90 DAY)) as RETARD
                    , DATE_ADD(dateEmprunt, INTERVAL 90 DAY) as dateRenduTheorique
                    ,IF(CURRENT_DATE()>DATE_ADD(dateEmprunt, INTERVAL 90 DAY),1,0) as flagRetard
                    ,IF(CURRENT_DATE()>DATE_ADD(dateEmprunt, INTERVAL 120 DAY),1,0) as flagPenalite
                    ,IF( ((DATEDIFF(curdate(),DATE_ADD(dateEmprunt, INTERVAL 120 DAY)) * 0.5)<25),
                         (DATEDIFF(curdate(),DATE_ADD(dateEmprunt, INTERVAL 120 DAY)) * 0.5),25) as dette
                    FROM ADHERENT
                    JOIN EMPRUNT ON EMPRUNT.idAdherent=ADHERENT.idAdherent
                    JOIN EXEMPLAIRE ON EMPRUNT.noExemplaire = EXEMPLAIRE.noExemplaire
                    JOIN OEUVRE ON EXEMPLAIRE.noOeuvre = OEUVRE.noOeuvre
                    WHERE dateRendu is NULL 
                    HAVING flagRetard=1
                    ORDER BY dateEmprunt DESC; ";

$reponse = $bdd->query($ma_requete_SQL);
$donnees = $reponse->fetchAll();

?>

<div class="contenu">
    <div class="row">
        <div class="title">Bilan des emprunts</div>
    </div>
    <div class="row">
        <div class="container">
            <div class="table-responsive-sm">
                <table class="table table-bordered table-hover">
                    <caption> Récapitulatif des Emprunts </caption>
                    <?php if(isset($donnees[0])){ ?>
                    <thead class="table-head">
                    <tr>
                        <th>Nom adherent</th>
                        <th>Titre de l'oeuvre</th>
                        <th>Date emprunt</th>
                        <th>N° Exemplaire</th>
                        <th>Retard (jours)</th>
                        <th>Pénalités (€)</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($donnees as $value) { ?>
                        <tr>
                            <td>
                                <?php echo $value['nomAdherent']; ?>
                            </td>
                            <td>
                                <?php echo($value['titre']); ?>
                            </td>
                            <td>
                                <?php echo date("d/m/Y", strtotime($value['dateEmprunt'])); ?>
                            </td>
                            <td>
                                <?php echo($value['noExemplaire']); ?>
                            </td>
                            <td>
                                <?php
                                if ($value['RETARD'] > 0) {
                                    echo($value['RETARD']);
                                } ?>
                            </td>
                            <td>
                                <?php
                                if ($value['dette'] > 0) {
                                    echo $value['dette']; ?><?php
                                } else {
                                    echo "0";
                                } ?>
                            </td>
                        </tr>
                    <?php }
                    } else{ ?>
                        <tr>
                            <td>
                                Pas d'oeuvre dans la base de données
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include ("v_foot.php");