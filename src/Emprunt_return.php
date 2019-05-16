<?php include("v_head.php");  ?>
<?php include ("v_nav.php");  ?>

<?php

include("connexion_bdd.php") ;

// include("index.php");


$ma_requete_SQL= "SELECT idAdherent, nomAdherent
                  FROM ADHERENT ORDER BY nomAdherent;";
$reponse = $bdd->query($ma_requete_SQL);
$donneesAdherent = $reponse->fetchAll();

if(isset($_POST['idAdherent']))
{

    $erreurs = array();
    $donnees['idAdherent'] = htmlentities($_POST['idAdherent']);
    $idAdherent = $_POST['idAdherent'];

    $ma_requete_SQL="
    SELECT OEUVRE.titre
    , EMPRUNT.dateEmprunt
    , EXEMPLAIRE.noExemplaire AS noExemplaireExistant
    , EMPRUNT.noExemplaire
    , EMPRUNT.idAdherent
    FROM EXEMPLAIRE
    INNER JOIN OEUVRE ON EXEMPLAIRE.noOeuvre = OEUVRE.noOeuvre
    INNER JOIN EMPRUNT ON EMPRUNT.noExemplaire=EXEMPLAIRE.noExemplaire
    WHERE EXEMPLAIRE.noExemplaire IS NOT NULL
    AND EMPRUNT.idAdherent=".$idAdherent."
    AND EMPRUNT.dateRendu IS NULL OR EMPRUNT.dateRendu =  '0000-00-00'
    ORDER BY OEUVRE.titre ASC, EXEMPLAIRE.noExemplaire ASC;";
    $reponse = $bdd->query($ma_requete_SQL);
    $donneesEmprunt = $reponse->fetchAll();

    if (empty($erreurs)) {
        /*
        $ma_requete_SQL="INSERT INTO EMPRUNT (idAdherent,noExemplaire,dateEmprunt,dateRendu)
                         VALUES ('".$donnees['idAdherent']."','".$donnees['noExemplaire']."','".$donnees['dateEmprunt_us']."','".$today['mday'].'-'.$today['mon'].'-'.$today['year']."');";
        $bdd->exec($ma_requete_SQL);
        header("Location: Emprunt_return.php");
        */
    }
}

?>

<div class="row">
    <div class="title">Gestion des retours</div>
</div>

<div class="row">
    <div class="container element-center">
        <form method="post" action="Emprunt_return.php">
            <?php if (! empty($erreurs)) echo '<div class="alert alter-danger">'.$message.'</div>';?>
            <div class="form-group">
            <label for="choixAdherent">Adherent : </label>
            <select class="form-control" id="choixAdherent">
                <!-- <?php if(!isset($donnees['idAdherent']) or $donnees['idAdherent'] == ""): ?>
                <option value="" selected disabled>Choisir l'adherent</option>
            <?php endif; ?> -->
                <?php foreach ($donneesAdherent as $adherent) : ?>
                    <option value="<?php echo $adherent['idAdherent']; ?>"
                        <?php if(isset($donnees['idAdherent']) and $donnees['idAdherent'] == $adherent['idAdherent']) echo "selected"; ?>
                    ><?php echo $adherent['nomAdherent']; ?></option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($erreurs['idAdherent'])) echo '<div class="alert alter-danger">'.$erreurs['idAdherent'].'</div>';?>

            </div>
            <?php if (isset($erreurs['idAdherent'])) echo '<div class="alert alter-danger">'.$erreurs['idAdherent'].'</div>';?>
            <div class="form-group scnd">
            <button type="submit" name="addEmprunt"  class="btn btn-primary">Valider</button>
            </div>
        </form>
    </div>
</div>


<div class="row">
    <?php
    if (isset($_POST['idAdherent']))
    {?>
        <table border="2">
            <caption>Récapitulatif des emprunts de l'adhérent <?php echo $_POST['idAdherent'] ?></caption>
            <thead>
            <tr> <th>Titre de l'oeuvre</th>
                <th>dateEmprunt</th>
                <th>noExemplaire</th>
                <th>Rendre</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($donneesEmprunt as $row): ?>
                <tr>
                    <td>
                        <?php echo $row["titre"]; ?>
                    </td>
                    <td>
                        <?php echo $row["dateEmprunt"]; ?>
                    </td>
                    <td>
                        <?php echo $row["noExemplaire"]; ?>
                    </td>
                    <td>
                        <a href="Emprunt_return2.php?idAdherent=<?= $row['idAdherent'] ?>&noExemplaire=<?= $row['noExemplaire']?>&dateEmprunt=<?= $row['dateEmprunt']?>">Rendre</a>
                    </td>
                </tr>
            <?php endforeach;?>
            </tbody>
        </table>
    <?php } ?>
</div>
<?php include ("v_foot.php");  ?>