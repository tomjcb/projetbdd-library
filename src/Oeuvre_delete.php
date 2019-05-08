<?php include("v_head.php");  ?>
<?php include ("v_nav.php");  ?>
<?php include ("connexion_bdd.php");?>



        <div> Êtes vous sûr de vouloir supprimer cette oeuvre ?</div>
    <form method="post" action="Oeuvre_delete.php?id=<?= $_GET['id'];?>&confirm=1">
        <button type="submit" class="btn btn-danger">Annuler</button>
    </form>
    <form method="post" action="Oeuvre_delete.php?id=<?= $_GET['id'];?>&confirm=2">
    <button type="submit" class="btn btn-success">Valider</button>
    </form>



<?php
if(isset($_GET["confirm"])){
    if($_GET["confirm"] == 2){
        if(isset($_GET["id"]) AND is_numeric($_GET["id"])){
            // ## accès au modèle
            $id=htmlentities($_GET['id']);
            $ma_requete_SQL= "DELETE FROM OEUVRE WHERE noOeuvre = ".$id.";";
            echo $ma_requete_SQL;
            $bdd->exec($ma_requete_SQL);

            header("Location: Oeuvre_show.php");
        }
    }
    else{
        header("Location: Oeuvre_show.php");
    }
}

?>