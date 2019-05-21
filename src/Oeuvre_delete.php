<?php include("v_head.php");  ?>
<?php include ("v_nav.php");  ?>
<?php include ("connexion_bdd.php");?>
<?php
$id = htmlentities($_GET['id']);
$ma_requete_SQL = "SELECT OEUVRE.noOeuvre,
    COUNT(e1.noExemplaire) AS nbrExemplaire
    FROM OEUVRE
    LEFT JOIN EXEMPLAIRE e1 ON e1.noOeuvre = OEUVRE.noOeuvre
    WHERE OEUVRE.noOeuvre = " . $id . ";";
$reponse = $bdd->query($ma_requete_SQL);
$donnees = $reponse->fetch();
?>

<div class="contenu">

<?php if($donnees['nbrExemplaire'] != 0 ){?>
    <div class="alert alert-danger"> Cette oeuvre est liée à <?php echo $donnees['nbrExemplaire']; ?> exemplaires. Êtes vous sûr de vouloir la supprimer ?</div>
<?php }
else{ ?>
    <div class="alert alert-danger"> Êtes vous sûr de vouloir supprimer cette oeuvre ? </div>
<?php } ?>
    <div class="row">
        <div class="confirmSuppr">
            <a class="btn btn-success" href="Oeuvre_delete.php?id=<?= $_GET['id'];?>&confirm=1">Valider</a>
            <a> | </a>
            <a class="btn btn-danger" href="Oeuvre_delete.php?id=<?=$_GET['id'];?>&confirm=2">Annuler </a>
        </div>
    </div>
<?php
if(isset($_GET["confirm"]) AND $_GET["confirm"] == 1) {
    if (isset($_GET["id"]) AND is_numeric($_GET["id"])) {
        // ## accès au modèle
        $id = htmlentities($_GET['id']);
        $ma_requete_SQL = "DELETE FROM OEUVRE WHERE noOeuvre = " . $id . ";";
        echo $ma_requete_SQL;
        $bdd->exec($ma_requete_SQL);

        header("Location: Oeuvre_show.php");
    }
}
if(isset($_GET["confirm"]) AND $_GET["confirm"] == 2) {
    header("Location: Oeuvre_show.php");
}

?>

</div>

<?php include ('v_foot.php'); ?>
