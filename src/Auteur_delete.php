<?php include("v_head.php");  ?>
<?php include ("v_nav.php");  ?>
<?php include ("connexion_bdd.php");


// 1ere appel:
if (isset($_GET['id']) and is_numeric($_GET['id'])){
    // ## accès au modèle:
    $id=htmlentities($_GET['id']);
    $ma_requete_SQL ="DELETE FROM AUTEUR WHERE idAuteur = ".$id.";";
    $bdd->exec($ma_requete_SQL);

    // ## redirection
    header("Location: Auteur_show.php");

}

?>
