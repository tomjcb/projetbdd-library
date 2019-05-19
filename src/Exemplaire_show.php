
<?php
include("v_head.php");
include("v_nav.php");

include('connexion_bdd.php');
// ## accès au modèle

if (isset($_GET["id"]) AND is_numeric($_GET["id"])){
    $id=htmlentities($_GET['id']);
    $ma_requete_SQL ="
    SELECT E1.noExemplaire, E1.etat, E1.dateAchat, E1.prix, OEUVRE.titre, OEUVRE.noOeuvre, OEUVRE.dateParution
    , E1.noExemplaire AS Exemplaire
    , E2.noExemplaire AS ExemplaireDispo
    , IF(E2.noExemplaire IS NULL, 'Emprunté', 'Disponible') as present
    FROM OEUVRE
    LEFT JOIN EXEMPLAIRE E1 ON E1.noOeuvre = OEUVRE.noOeuvre
    LEFT JOIN EXEMPLAIRE E2 ON E2.noExemplaire = E1.noExemplaire
    AND E2.noExemplaire NOT IN (SELECT EMPRUNT.noExemplaire FROM EMPRUNT WHERE EMPRUNT.dateRendu IS NULL)
      WHERE OEUVRE.noOeuvre = ".$id."
    ORDER BY E2.noExemplaire DESC;
    ";
    $reponse = $bdd->query($ma_requete_SQL);
    $donnees = $reponse->fetchAll();




    $requete_SQL="SELECT OEUVRE.titre, AUTEUR.nomAuteur, AUTEUR.prenomAuteur, OEUVRE.dateParution
                    FROM OEUVRE
                    INNER JOIN AUTEUR ON AUTEUR.idAuteur = OEUVRE.idAuteur
                    WHERE OEUVRE.noOeuvre = ".$id.";";
    $result = $bdd->query($requete_SQL);
    $donneesOeuvre = $result->fetch();
}

// ## affichage de la vue
?>

<div class="row">
    <div class="title">Gestion des exemplaires</div>
</div>

<div class="row">
    <div class="container">
        <div class="row">
        <div class="title_oeuvre">
        Titre : <?php echo $donneesOeuvre["titre"]; ?> <br>
        Auteur : <?php echo $donneesOeuvre['nomAuteur']; ?> <br>
        Date Parution : <?php echo $donneesOeuvre['dateParution']; ?> <br> <br>
        </div>
        </div>
        <table class="table table-bordered table-hover">
            <caption> Recapitulatifs des Exemplaires </caption>
            <?php if(isset($donnees[0])): ?>
                <thead class="table-head">
                <tr><th>N° Exemplaire</th>
                    <th>Etat</th>
                    <th>Date d'Achat</th>
                    <th>Prix</th>
                    <th>Etat</th>
                    <th>Opération</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($donnees as $value): ?>
                    <tr>
                        <td>
                            <?php echo $value['noExemplaire']; ?>
                        </td>
                        <td>
                            <?php echo($value['etat']); ?>
                        </td>
                        <td>
                            <?php echo date("d/m/Y", strtotime($value['dateAchat'])) ; ?>
                        </td>
                        <td>
                            <?php echo($value['prix']); ?>
                        </td>
                        <td>
                            <?php echo($value['present']); ?>
                        </td>
                        <td>
                            <a title="Modifier" class="btn btn-info" href="Exemplaire_edit.php?id=<?php echo $value['noExemplaire']; ?>"><i class="fa fa-wrench"></i></a>
                            <a> | </a>
                            <a title="Supprimer" class="btn btn-danger" href="Exemplaire_delete.php?id=<?=$value['noExemplaire'];?>&idoeuvre=<?= $id; ?>&present=<?= $value['present'] ?>&confirm=0"><i class="fa fa-trash"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            <?php else: ?>
                <tr>
                    <td>
                        Pas d'exemplaire dans la base de données
                    </td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
</div>

<div class="row">
    <div class="container scnd">
        <a class="btn btn-lg btn-primary" href="Exemplaire_add.php?id=<?php echo $id; ?>"> Ajouter un Exemplaire </a>
    </div>
</div>

<?php include("v_foot.php");?>
