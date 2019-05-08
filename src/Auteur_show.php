<?php include("v_head.php");  ?>
<?php include ("v_nav.php");  ?>
<?php include ("connexion_bdd.php");

// ## accès au modèle
$ma_requete_SQL = " SELECT AUTEUR.nomAuteur, AUTEUR.idAuteur, AUTEUR.prenomAuteur,
                    COUNT(OEUVRE.noOeuvre) AS nbrOeuvre
                    FROM OEUVRE
                    RIGHT JOIN AUTEUR ON OEUVRE.idAuteur = AUTEUR.idAuteur
                    GROUP BY AUTEUR.idAuteur
                    ORDER BY AUTEUR.nomAuteur; ";

$reponse = $bdd->query($ma_requete_SQL);
$donnees = $reponse->fetchAll();

// ## affichage de la vue
?>

<div class="row">
    <a href="Auteur_add.php"> Ajouter un Auteur </a>
    <table border="2">
        <caption> Récapitulatifs des Auteurs </caption>
        <?php if(isset($donnees[0])): ?>
            <thead>
            <tr><th>Nom</th>
                <th>Prénom</th>
                <th>Nombre d'oeuvres</th>
                <th>Opération</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($donnees as $value): ?>
                <tr>
                    <td>
                        <?php echo $value['nomAuteur']; ?>
                    </td>
                    <td>
                        <?php echo($value['prenomAuteur']); ?>
                    </td>
                    <td>
                        <center><?php echo $value['nbrOeuvre']; ?></center>
                    </td>
                    <td>
                        <a class="btn btn-primary" href="Auteur_edit.php?id=<?= $value['idAuteur']; ?>"> Modifier </a>
                        <a> | </a>
                        <a class="btn btn-danger" href="Auteur_delete.php?id=<?= $value['idAuteur']; ?>"> Supprimer </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        <?php else: ?>
            <tr>
                <td>
                    Pas d'auteur dans la base de données
                </td>
            </tr>
        <?php endif; ?>
    </table>
</div>
