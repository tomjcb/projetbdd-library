<?php include("v_head.php");  ?>
<?php include ("v_nav.php");  ?>

<?php include ("connexion_bdd.php");
$idAdherent = $_GET['idAdherent'];
$selec = false;

if (isset($_GET['idAdherent'])){
    $idAdherent = $_GET['idAdherent'];

    $ma_requete_sql1 ="SELECT adherent.idAdherent, adherent.nomAdherent, OEUVRE.titre, emprunt.dateEmprunt, emprunt.dateRendu, exemplaire.noExemplaire, 
            DATEDIFF(curdate(), DATE_ADD(EMPRUNT.dateEmprunt, INTERVAL 90 DAY)) AS RETARD,
            DATEDIFF(EMPRUNT.dateRendu, EMPRUNT.dateEmprunt) AS diffEmprRendu,
            DATEDIFF(curdate(),dateEmprunt) as nbJoursEmprunt
            FROM adherent
            JOIN emprunt ON adherent.idAdherent=emprunt.idAdherent
            JOIN exemplaire ON emprunt.noExemplaire = exemplaire.noExemplaire
            JOIN oeuvre ON exemplaire.noOeuvre = oeuvre.noOeuvre
            WHERE adherent.idAdherent = ". $idAdherent ."
            ORDER BY Exemplaire.noExemplaire ;";
    $reponse1 = $bdd->query($ma_requete_sql1);
    $donnees1 = $reponse1->fetchAll();

    $ma_requete_sql2 ="SELECT nomAdherent FROM adherent where idAdherent = ". $idAdherent .";";
    $reponse2 = $bdd->query($ma_requete_sql2);
    $donnees2 = $reponse2->fetchAll();
}

if (isset($_GET['idAdherent']) && isset($_GET['noExemplaire']) && isset($_GET['dateEmprunt'])){
    $idAdherent = $_GET['idAdherent'];
    $noExemplaire = $_GET['noExemplaire'];
    $dateEmprunt = ($_GET['dateEmprunt']);

    $ma_requete_sql3 = "DELETE FROM EMPRUNT 
                        WHERE (idAdherent = ".$idAdherent." AND noExemplaire = ".$noExemplaire." AND dateEmprunt ='".$dateEmprunt."' ); 
                        ";

    $bdd->exec($ma_requete_sql3);
    header('Location: Emprunt_delete.php?idAdherent='.$idAdherent);

}

if (isset($_GET['idAdherent']) && isset($_GET['confirm'])) {
    $idAdherent = $_GET['idAdherent'];

    $ma_requete_sql3 = "DELETE FROM EMPRUNT 
                        WHERE idAdherent = ".$idAdherent."; 
                        ";

    $bdd->exec($ma_requete_sql3);
    header('Location: Emprunt_delete.php?idAdherent='.$idAdherent);

}

?>

<div class="contenu">

<div class="row">
    <div class="container">
        <div class="title-2">
            <?php foreach ($donnees2 as $value){ ?>
            Aperçu des emprunts de <?php echo $value['nomAdherent']; ?>
            <?php } ?>
        </div>
        <div class="row">
            <div class="container scnd">
                <a class="btn btn-lg btn-primary" href="Emprunt_show.php"> Changer d'adhérent </a>
            </div>
        </div>
        <div class="table-responsive-sm">
            <table class="table table-bordered table-hover">
                <?php if (isset($donnees1[0])) { ?>
                    <thead class="table-head">
                    <tr>
                        <th>Titre</th>
                        <th>Date d'emprunt</th>
                        <th>Date de rendu</th>
                        <th>N° exemplaire</th>
                        <th>Durée d'emprunt</th>
                        <th>Retard</th>
                        <th>Opération</th>
                    </tr>
                    </thead>
                    <?php foreach ($donnees1 as $value){ ?>
                        <tr>
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
                                } else {
                                    echo "";
                                } ?>

                            </td>
                            <td>
                                <?php echo $value['noExemplaire']; ?>
                            </td>
                            <td>
                                <?php
                                if ($value['dateRendu'] != "") {
                                    echo $value['diffEmprRendu'];
                                } else {
                                    echo $value['nbJoursEmprunt'];
                                }
                                ?>
                            </td>
                            <td class="retard">
                                <?php
                                if ($value['dateRendu'] == "") {
                                    if ($value['RETARD'] > 0) {
                                        echo $value['RETARD'];
                                    }
                                }
                                ?>
                            </td>
                            <td>
                                <a class="btn btn-danger"
                                   href="Emprunt_delete.php?idAdherent=<?php echo $value['idAdherent']; ?>&noExemplaire=<?php echo $value['noExemplaire']; ?>&dateEmprunt=<?php echo $value['dateEmprunt']; ?>&dateRendu=<?php echo $value['dateRendu']; ?>"><i
                                        class="fa fa-trash"></i></a>
                                <a class="btn btn-info"
                                   href="Emprunt_edit.php?id=<?php echo $value['noExemplaire']; ?>"><i
                                        class="fa fa-wrench"></i></a>
                            </td>
                        </tr>
                    <?php } ?>
                <?php }else{ ?>
                    <tr>
                        <td>Pas d'enregistrements</td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</div>
<?php if (isset($donnees1[0])) { ?>
<div class="row">
    <div class="container scnd">
        <?php foreach ($donnees2 as $value){ ?>
            <a class="btn btn-danger" href="Emprunt_deleteAll.php?idAdherent=<?php echo $_GET['idAdherent'];?>">Supprimer tous les emprunts de <?php echo $value['nomAdherent']; ?></a>
        <?php }?>
    </div>
</div>
<?php } ?>

</div>

<?php include ('v_foot.php'); ?>