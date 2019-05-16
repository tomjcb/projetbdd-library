<?php
include("index.php");

if(isset($_POST['idAdherent']) AND isset($_POST['noExemplaire']) AND isset($_POST['dateEmprunt']))
{
    $erreurs=array();
    $donnees['idAdherent']=htmlentities($_POST['idAdherent']);
    $donnees['noExemplaire']=htmlentities($_POST['noExemplaire']);
    $donnees['dateEmprunt']=htmlentities($_POST['dateEmprunt']);

    if (!preg_match("/[1-9]{1,}/", $donnees['idAdherent'])) {
        $erreurs['idAdherent'] = "L\'ID de l'adhérent est invalide";
    }

    if (!preg_match("/[1-9]{1,}/", $donnees['noExemplaire'])) {
        $erreurs['noExemplaire'] = "L'exemplaire selectionné ne peut pas être emprunté";
    }

    if (!preg_match("#^([0-9]{1,2})-([0-9]{1,2})-([0-9]{4})$#", $donnees['dateEmprunt'], $matches))
        $erreurs['dateEmprunt']='La date d\'emprunt doit être au format JJ/MM/AAAA';

    else if (!checkdate($matches[2], $matches[1], $matches[3])) $erreurs['dateEmprunt']="La date n'est pas valide";

    else $donnees['dateEmprunt_us']=$matches[3]."-".$matches[2]."-".$matches[1];

    if (empty($erreurs)) {
        $ma_requete_SQL = "INSERT INTO EMPRUNT (idAdherent,noExemplaire,dateEmprunt) VALUES ('".$donnees['idAdherent']."','".$donnees['noExemplaire']."','".$donnees['dateEmprunt_us']."');";
        $bdd->exec($ma_requete_SQL);
        header("Location: Emprunt_show.php");
    }
}

$ma_requete_SQL="SELECT idAdherent, nomAdherent FROM ADHERENT ORDER BY nomAdherent;";
$reponse = $bdd->query($ma_requete_SQL);
$donneesAdherent = $reponse->fetchAll();
$ma_requete_SQL="
    SELECT E2.noExemplaire
    , EXEMPLAIRE.noExemplaire AS noExemplaireExistant
    , E2.etat
    , OEUVRE.titre
    , OEUVRE.noOeuvre
    FROM EXEMPLAIRE
    INNER JOIN OEUVRE
    ON EXEMPLAIRE.noOeuvre = OEUVRE.noOeuvre
    LEFT JOIN EXEMPLAIRE E2
    ON E2.noExemplaire = EXEMPLAIRE.noExemplaire
    AND E2.noExemplaire NOT IN (SELECT EMPRUNT.noExemplaire FROM EMPRUNT WHERE EMPRUNT.dateRendu IS NULL OR EMPRUNT.dateRendu = '0000-00-00')
    WHERE E2.noExemplaire IS NOT NULL
    ORDER BY OEUVRE.titre ASC, E2.noExemplaire ASC";
$reponse = $bdd->query($ma_requete_SQL);
$donneesExemplaire = $reponse->fetchAll();
$today = getdate();

?>
<form method="post" action="Emprunt_add4.php">
    <div class="row">
        <fieldset>
            <legend>Ajouter une Emprunt</legend>
            <?php if (! empty($erreurs)) echo '<div class="alert alter-danger">'.$message.'</div>';?>
            <label>Adherent :
                <select name="idAdherent">
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
            </label>
            <?php if (isset($erreurs['idAdherent'])) echo '<div class="alert alter-danger">'.$erreurs['idAdherent'].'</div>';?>
            <br>
            <br>
            <label>Numéro d'exemplaire :
                <select name="noExemplaire">
                    <!-- <?php if(!isset($donnees['noExemplaire']) or $donnees['noExemplaire'] == ""): ?>
                        <option value="" selected disabled>Choisir le n° d'oeuvre</option>
                    <?php endif; ?> -->
                    <?php foreach ($donneesExemplaire as $exemplaire) : ?>
                        <option value="<?php echo $exemplaire['noExemplaire']; ?>"
                            <?php if(isset($donnees['noExemplaire']) and $donnees['noExemplaire'] == $exemplaire['noExemplaire']) echo "selected"; ?>
                        ><?php echo $exemplaire['titre']." -- ".$exemplaire['noExemplaire']." (".$exemplaire['etat'].")"; ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($erreurs['noExemplaire'])) echo '<div class="alert alter-danger">'.$erreurs['noExemplaire'].'</div>';?>
            </label>
            <?php if (isset($erreurs['noExemplaire'])) echo '<div class="alert alter-danger">'.$erreurs['noExemplaire'].'</div>';?>
            <br>

            <br>
            <label>Date d'emprunt
                <?php
                if (isset($donnees['dateEmprunt'])) echo '<input name="dateEmprunt" type="text" size="18" value="'.date("d/m/Y", strtotime($value['dateEmprunt'])).'"/>';
                else echo '<input name="dateEmprunt" type="text" size="18" value="'.$today['mday'].'-'.$today['mon'].'-'.$today['year'].'"/>';
                ?>
            </label>
            <?php if (isset($erreurs['dateEmprunt'])) echo '<div class="alert alter-danger">'.$erreurs['dateEmprunt'].'</div>';?>

            <input type="submit" name="addEmprunt" value="Ajouter"/>
        </fieldset>
    </div>
</form>
