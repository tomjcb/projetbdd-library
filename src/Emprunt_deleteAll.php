<?php include("v_head.php");  ?>
<?php include ("v_nav.php");  ?>

<?php include ("connexion_bdd.php"); ?>

<?php

if (isset($_GET['idAdherent'])){
    $idAdherent = $_GET['idAdherent'];

    $ma_requete_sql2 ="SELECT nomAdherent FROM adherent where idAdherent = ". $idAdherent .";";
    $reponse2 = $bdd->query($ma_requete_sql2);
    $donnees2 = $reponse2->fetchAll();
}

?>

<div class="contenu">

<div class="row">
    <div class="container">
        <div class="title-2">
            <?php foreach ($donnees2 as $value){ ?>
                Supprimer tous les emprunts de <?php echo $value['nomAdherent']; ?>
            <?php } ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="container">
        <div class="alert alert-danger"> Certains emprunts peuvent être encore en cours. <br> Êtes-vous sur de tout vouloir supprimer ? </div>
    </div>
</div>
<div class="row">
    <div class="confirmSuppr">
        <a class="btn btn-success" href="Emprunt_delete.php?idAdherent=<?php echo $_GET['idAdherent'];?>&confirm=1">Valider</a>
        <a> | </a>
        <a class="btn btn-danger" href="Emprunt_delete.php?idAdherent=<?php echo $_GET['idAdherent'];?>">Annuler </a>
    </div>
</div>

</div>

<?php include ('v_foot.php'); ?>