<?php include("v_head.php");  ?>
<?php include ("v_nav.php");  ?>

<div class="row">
    <div class="title">Gestion des emprunts</div>
</div>


<?php

include("connexion_bdd.php");

$requete ="SELECT * , DATEDIFF(DATE(NOW()), DATE_ADD(EMPRUNT.dateEmprunt, INTERVAL 30 DAY)) AS retard,
            DATEDIFF(DATE_ADD(EMPRUNT.dateRendu, INTERVAL 30 DAY), EMPRUNT.dateRendu) AS diffEmprRendu
            FROM adherent
            JOIN emprunt ON adherent.idAdherent=emprunt.idAdherent
            JOIN exemplaire ON emprunt.noExemplaire = exemplaire.noExemplaire
            JOIN oeuvre ON exemplaire.noOeuvre = oeuvre.noOeuvre;";
$reponse = $bdd->query($requete);
$donnees = $reponse->fetchAll();

?>

<div class="row">
    <div class="container">
        <div class="table-responsive-sm">
            <table class="table table-bordered table-hover">
                <?php if (isset($donnees[0])): ?>
                <thead class="table-head">
                <tr>
                    <th>Nom adherent</th>
                    <th>Titre</th>
                    <th>Date d'emprunt</th>
                    <th>Date de rendu</th>
                    <th>Exemplaires</th>
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
                            <?php echo $value['dateEmprunt']; ?>
                        </td>
                        <td>
                            <?php echo $value['dateRendu']; ?>
                        </td>
                        <td>
                            <?php echo $value['noExemplaire']; ?>
                        </td>
                        <td>
                            <?php
                            if ($value['dateRendu'] == "" || $value['dateRendu'] == NULL || $value['dateRendu'] == 01/01/1970) {
                                echo $value['retard'];
                            }else {
                                echo $value['diffEmprRendu'];
                            }
                            ?>
                        </td>
                        <td>
                            <a class="btn btn-danger" href="Emprunt_delete.php?idAdherent=<?php echo $value['idAdherent'];?>&noExemplaire=<?php echo $value['noExemplaire'];?>&dateEmprunt=<?php echo $value['dateEmprunt'];?>&daterendu=<?php echo $value['dateRendu'];?>"><i class="fa fa-trash"></i></a>
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
?>

<?php include ("v_foot.php");  ?>
