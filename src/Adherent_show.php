<?php include("v_head.php");  ?>
<?php include ("v_nav.php");  ?>
<?php include ("connexion_bdd.php");

$ma_requete_SQL = "SELECT ADHERENT.nomAdherent, ADHERENT.idAdherent, ADHERENT.adresse, ADHERENT.datePaiement,
                  IF(CURRENT_DATE()>DATE_ADD(datePaiement, INTERVAL 1 YEAR),'Paiement en retard depuis le ',0) as retard,
                   DATE_ADD(datePaiement, INTERVAL 1 YEAR) as datePaiementFutur
                   FROM ADHERENT
                   ORDER BY ADHERENT.nomAdherent; ";

$reponse = $bdd->query($ma_requete_SQL);
$donnees = $reponse->fetchAll();

$ma_requete_SQL2 = "SELECT ADHERENT.nomAdherent, count(EMPRUNT.noExemplaire) as nbEmprunt FROM ADHERENT
INNER JOIN EMPRUNT on EMPRUNT.idAdherent = ADHERENT.idAdherent
GROUP BY ADHERENT.nomAdherent;";
$reponse2 = $bdd->query($ma_requete_SQL2);
$donnees2 = $reponse2->fetchAll();
?>

<div class="row">
    <a href="Adherent_add.php"> Ajouter un Adherent </a>
    <table border="2">
        <caption> Recapitulatifs des Adhérents </caption>
        <?php if(isset($donnees[0]) || isset($donnees2)): ?>
            <thead>
            <tr><th>Nom</th>
                <th>Adresse</th>
                <th>datePaiement</th>
                <th>information</th>
                <th>Opération</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($donnees as $value): ?>
                <tr>
                    <td>
                        <?php echo($value['nomAdherent']); ?>
                    </td>
                    <td>

                        <?php echo $value['adresse']; ?>
                    </td>
                    <td>
                        <?php echo date("d/m/Y", strtotime($value['datePaiement'])); ?>
                    </td>
                    <td>

                        <?php foreach ($donnees2 as $value2): ?>
                            <?php if ($value["nomAdherent"] == $value2["nomAdherent"]): ?>
                                <?php echo ($value2['nbEmprunt']) ?>
                                <?php echo " exemplaire(s) emprunté(s)" ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        </br>
                        <?php

                        if ($value['retard'] != '0') {
                            echo $value['retard'], date("d/m/Y", strtotime($value['datePaiementFutur']));
                        }else {
                            echo " ";
                        }
                        ?>
                        </br>

                    </td>
                    <td>
                        <a href="Adherent_edit.php?id=<?= $value['idAdherent']; ?>"> Modifier </a>
                        <a href="Adherent_delete.php?id=<?= $value['idAdherent']; ?>"> Supprimer </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        <?php else: ?>
            <tr>
                <td>
                    Pas d'Adherent dans la base de données
                </td>
            </tr>
        <?php endif; ?>
    </table>
</div>