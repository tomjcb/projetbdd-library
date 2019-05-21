<?php include("v_head.php");  ?>
<?php include ("v_nav.php");  ?>
<?php include ("connexion_bdd.php");
// ## accès au modèle
$ma_requete_SQL = "SELECT AUTEUR.nomAuteur, OEUVRE.titre, OEUVRE.noOeuvre, OEUVRE.dateParution,
                    COUNT(e1.noExemplaire) AS nbrExemplaire,
                    COUNT(e2.noExemplaire) AS nbrDisponible
                    FROM OEUVRE
                    INNER JOIN AUTEUR ON AUTEUR.idAuteur = OEUVRE.idAuteur
                    LEFT JOIN EXEMPLAIRE e1 ON e1.noOeuvre = OEUVRE.noOeuvre
                    LEFT JOIN EXEMPLAIRE e2 ON e2.noExemplaire = e1.noExemplaire
AND e2.noExemplaire NOT IN (
    SELECT EMPRUNT.noExemplaire
                            FROM EMPRUNT
                            WHERE EMPRUNT.dateRendu IS NULL)
                    GROUP BY OEUVRE.noOeuvre
                    ORDER BY AUTEUR.nomAuteur ASC, OEUVRE.titre ASC; ";

$reponse = $bdd->query($ma_requete_SQL);
$donnees = $reponse->fetchAll();

// ## affichage de la vue
?>

<div class="contenu">

<div class="row">
    <div class="title">Gestion des oeuvres et exemplaires</div>
</div>
<div class="row">
    <div class="container">
        <div class="table-responsive-sm">
        <table class="table table-bordered table-hover">
            <caption> Récapitulatif des œuvres </caption>
            <?php if(isset($donnees[0])): ?>
                <thead class="table-head">
                <tr>
                    <th>Nom de auteur</th>
                    <th>Titre de l'oeuvre</th>
                    <th>Date de parution</th>
                    <th>Nbr. </th>
                    <th>Nbr. disponible </th>
                    <th>Exemplaires</th>
                    <th>Opérations</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($donnees as $value): ?>
                    <tr>
                        <td>
                            <?php echo $value['nomAuteur']; ?>
                        </td>
                        <td>
                            <?php echo($value['titre']); ?>
                        </td>
                        <td>
                            <?php echo date("d/m/Y", strtotime($value['dateParution'])) ; ?>
                        </td>
                        <td>
                            <?php echo($value['nbrExemplaire']); ?>
                        </td>
                        <td>
                            <?php echo($value['nbrDisponible']); ?>
                        </td>
                        <td>
                            <a class="btn btn-secondary" role="button" href="Exemplaire_show.php?id=<?php echo $value['noOeuvre']; ?>">Gérer les Exemplaires</a>
                        </td>
                        <td>
                            <a class="btn btn-info" href="Oeuvre_edit.php?id=<?= $value['noOeuvre']; ?>"><i class="fa fa-wrench"></i></a>
                            <a> | </a>
                            <a class="btn btn-danger" href="Oeuvre_delete.php?id=<?=$value['noOeuvre'];?>&confirm=0"><i class="fa fa-trash"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            <?php else: ?>
                <tr>
                    <td>
                        Pas d'oeuvre dans la base de données
                    </td>
                </tr>
            <?php endif; ?>
        </table>
        </div>
    </div>
</div>
<div class="row">
    <div class="container scnd">
        <a class="btn btn-lg btn-primary" href="Oeuvre_add.php" role="button"> Ajouter une oeuvre </a>
    </div>
</div>

</div>
<?php include ("v_foot.php");  ?>
