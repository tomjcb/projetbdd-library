<?php
include("v_nav.php");
include("v_head.php");

include("connexion_bdd.php");
if(isset($_GET["id"]) AND isset($_GET['idoeuvre']) AND isset($_GET['present'])) {
    if($_GET['present'] == 'Emprunté' ){?>
        <div class="alert alert-danger"> Cet exemplaire est emprunté. Êtes vous sûr de vouloir le supprimer ?</div>
        <?php
    }
    else{ ?>
        <div class="alert alert-danger"> Êtes vous sûr de vouloir supprimer cet exemplaire ? </div>
        <?php
    }
    ?>
    <div class="row">
        <div class="confirmSuppr">
            <a class="btn btn-success" href="Exemplaire_delete.php?id=<?=$_GET['id'];?>&idoeuvre=<?= $_GET['idoeuvre']; ?>&present=<?= $_GET['present'] ?>&confirm=1">Valider</a>
            <a> | </a>
            <a class="btn btn-danger" href="Exemplaire_delete.php?id=<?=$_GET['id'];?>&idoeuvre=<?= $_GET['idoeuvre']; ?>&present=<?= $_GET['present'] ?>&confirm=2">Annuler </a>
        </div>
    </div>


    <?php
    // ## acces au modèle
    $numero=htmlentities($_GET["id"]);
    $idoeuvre=htmlentities($_GET['idoeuvre']);

    if(isset($_GET["confirm"]) AND $_GET["confirm"] == 1) {
        $ma_requete_SQL = "DELETE FROM EXEMPLAIRE WHERE noExemplaire = " . $numero . ";";
        var_dump($_GET);
        var_dump($ma_requete_SQL);
        $bdd->exec($ma_requete_SQL);

        // ## redirection
        header("Location: Exemplaire_show.php?id=$idoeuvre");
    }
    if(isset($_GET["confirm"]) AND $_GET["confirm"] == 2) {
        header("Location: Exemplaire_show.php?id=$idoeuvre");
    }

}
include("v_foot.php")  ?>
