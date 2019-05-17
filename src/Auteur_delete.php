<?php include("v_head.php");  ?>
<?php include ("v_nav.php");  ?>
<?php include ("connexion_bdd.php");
$id=htmlentities($_GET['id']);
$ma_requete_SQL = " SELECT AUTEUR.idAuteur,
                    COUNT(OEUVRE.noOeuvre) AS nbrOeuvre
                    FROM OEUVRE
                    RIGHT JOIN AUTEUR ON OEUVRE.idAuteur = AUTEUR.idAuteur
                    WHERE AUTEUR.idAUTEUR = ". $id . "; ";

$reponse = $bdd->query($ma_requete_SQL);
$donnees = $reponse->fetch();?>

<?php if($donnees['nbrOeuvre'] != 0 ){?>
    <div class="alert alert-danger"> Cet auteur est liée à <?php echo $donnees['nbrOeuvre']; ?> oeuvre(s). Êtes vous sûr de vouloir le supprimer ?</div>
<?php }
else{ ?>
    <div class="alert alert-danger"> Êtes vous sûr de vouloir supprimer cet auteur ? </div>
<?php } ?>
<div class="row">
    <div class="confirmSuppr">
        <a class="btn btn-success" href="Auteur_delete.php?id=<?= $_GET['id'];?>&confirm=1">Valider</a>
        <a> | </a>
        <a class="btn btn-danger" href="Auteur_delete.php?id=<?=$_GET['id'];?>&confirm=2">Annuler </a>
    </div>
</div>
<?php
if(isset($_GET["confirm"]) AND $_GET["confirm"] == 1) {
    if (isset($_GET['id']) and is_numeric($_GET['id'])) {
        // ## accès au modèle:
        $id = htmlentities($_GET['id']);
        $ma_requete_SQL = "DELETE FROM AUTEUR WHERE idAuteur = " . $id . ";";
        $bdd->exec($ma_requete_SQL);

        // ## redirection
        header("Location: Auteur_show.php");

    }
}
if(isset($_GET["confirm"]) AND $_GET["confirm"] == 2) {
    header("Location: Auteur_show.php");
}

?>
