

<!-- <?php

include('connexion_bdd.php');


$selec = false;

$requete_adherent = "SELECT * FROM ADHERENT;";
$reponse_adherent = $bdd->query($requete_adherent);
$donnees_adherent = $reponse_adherent->fetchAll();


$requete3 = "SELECT OEUVRE.titre, EXEMPLAIRE.noOeuvre
              FROM OEUVRE
              JOIN EXEMPLAIRE ON EXEMPLAIRE.noOeuvre = OEUVRE.noOeuvre
              HAVING COUNT(EXEMPLAIRE.noOeuvre) > 0 ;";
$reponse3 = $bdd->query($requete3);
$donnees_auteur2 = $reponse3->fetchAll();


if (isset($_POST['noExemplaire']) && isset($_POST['dateEmprunt']) && isset($_POST['idAdherent'])) {

  $_SESSION["heure"] = date('H');
  $_SESSION["minute"] = date('i');
  $_SESSION["seconde"] = date('s');
  $_SESSION["insertion"] = 0;
  $idAdherent = $_POST['idAdherent'];
  $dateEmprunt = $_POST['dateEmprunt'];
  $dateRendu = $_POST['daterendu'];
  $noExemplaire = $_POST['noExemplaire'];

  $sql = "INSERT INTO EMPRUNT (noExemplaire,dateEmprunt, idAdherent)
          VALUES ($noExemplaire, '$dateEmprunt', $idAdherent)";
  $pos = strpos($sql,'#');

  if($pos == false){
        $res = $con->exec($sql);
        if ($res == 1) {
        $_SESSION["insertion"] = 1;
        }
      } else{
        $_SESSION["insertion"] = 0;
    }

  header("Location: Emprunt_show.php?changement=insertion");

}

if (isset($_POST['Oeuvre'])){
  $noOeuvre = $_POST['Oeuvre'];
  $selec = true;

  $requete_exemplaire = "SELECT EXEMPLAIRE.noExemplaire, EXEMPLAIRE.noOeuvre, OEUVRE.titre
                        FROM EXEMPLAIRE
                        JOIN OEUVRE ON OEUVRE.noOeuvre = EXEMPLAIRE.noOeuvre
                        WHERE EXEMPLAIRE.noExemplaire NOT IN ( SELECT EMPRUNT.noExemplaire
                                                                FROM EMPRUNT
                                                                WHERE EMPRUNT.dateRendu IS NULL OR EMPRUNT.dateRendu = '0000-00-00')
                        AND EXEMPLAIRE.noOeuvre = $noOeuvre";
  $reponse_exemplaire = $bdd->query($requete_exemplaire);
  $donnees_exemplaire = $reponse_exemplaire->fetchAll();
}

 ?>

 <?php include("v_head.php"); ?>
 <body>
   <?php include('v_nav.php'); ?>
   <div class="container">
     <div class="row">
       <div class="col-md-6">
         <?php $sql ?>

         <?php if($selec == false): ?>
           <form class="form" action="Emprunt_add.php" method="post">
             <div class="form-group">
               <label>Oeuvre</label>
               <select class="form-control" type="text" name="Oeuvre">
                 <option value="choix">choix</option>
                 <?php foreach ($donnees_auteur2 as $row): ?>
                   <option value="<?php echo $row['noOeuvre'];?>"><?php echo $row['titre'];?></option>
                 <?php endforeach; ?>
               </select>
               <input name="addEmprunt" type="submit" value="Valider">
             </div>
           </form>

         <?php endif; ?>
         <?php if($selec == true): ?>
           <form class="form" action="Emprunt_add.php" method="post">

             <div class="form-group">
               <label>Exemplaire</label>
               <?php $sql ?>
               <select class="form-control" type="text" name="noExemplaire">
                 <option value="choix">choix</option>
                 <?php foreach ($donnees_exemplaire as $row): ?>
                   <option value="<?php echo $row['noExemplaire'];?>"><?php echo $row['noExemplaire']." - ".$row['titre'];?></option>
                 <?php endforeach; ?>
               </select>
             </div>

             <div class="form-group">
               <label>Date de Emprunt</label>
               <input type="date" name="dateEmprunt" placeholder="aaaa/mm/jj" value="<?= date('Y-m-d')?>">
             </div>

             <div class="form-group">
               <label>idAdherent</label>
               <select class="" name="idAdherent">
                 <option value="choix">choix</option>
                 <?php foreach ($donnees_adherent as $row_adherent): ?>
                   <option value="<?php echo $row_adherent['idAdherent'];?>"><?php echo $row_adherent['nomAdherent'];?></option>
                 <?php endforeach; ?>
               </select>
             </div>

             <input name="addEmprunt" type="submit" value="Ajouter un Emprunt">

           </form>
         <?php endif; ?>
       </div>
     </div>
   </div>
 </body>


 -->




<!--
<?php
include("v_head.php");
include("v_nav.php");

include('connexion_bdd.php');

$ma_requete_SQLAdherent = "SELECT ADHERENT.nomAdherent, ADHERENT.idAdherent
                            FROM ADHERENT ;";
$reponseAdherent = $bdd->query($ma_requete_SQLAdherent);
$donneesAdherent = $reponseAdherent->fetchAll();

$ma_requete_SQLExemplaire = "SELECT EXEMPLAIRE.noExemplaire, EXEMPLAIRE.noOeuvre, OEUVRE.titre
                            FROM EXEMPLAIRE
                            INNER JOIN OEUVRE ON EXEMPLAIRE.noOeuvre = OEUVRE.noOeuvre
                            WHERE EXEMPLAIRE.noExemplaire NOT IN (SELECT EMPRUNT.noExemplaire FROM EMPRUNT WHERE (dateRendu IS NULL OR dateRendu='0000-00-00') );";
$reponseExemplaire = $bdd->query($ma_requete_SQLExemplaire);
$donneesExemplaire = $reponseExemplaire->fetchAll();

// Traitement:
if (isset($_POST["idAdherent"]) AND isset($_POST["noExemplaire"])){

    $idAdherent = $_POST["idAdherent"];
    $ma_requete_SQLNombreEmprunt = "SELECT COUNT(emp.noExemplaire) AS nombreEmprunt, ad.idAdherent
                                    FROM EMPRUNT emp
                                    INNER JOIN EXEMPLAIRE ex ON ex.noExemplaire = emp.noExemplaire
                                    INNER JOIN OEUVRE oe ON oe.noOeuvre = ex.noOeuvre
                                    INNER JOIN ADHERENT ad ON ad.idAdherent = emp.idAdherent
                                    WHERE emp.idAdherent =".$idAdherent." and (emp.dateRendu IS NULL OR dateRendu='0000-00-00');";
    $reponseNombreEmprunt = $bdd->query($ma_requete_SQLNombreEmprunt);
    $donneesNombreEmprunt = $reponseNombreEmprunt->fetch();

    // Limitation de 5 emprunts
    if ($donneesNombreEmprunt['nombreEmprunt']>=5){
        $erreurs['nombreEmprunt'] = "Cet adhérent possède 5 livres en cours d'emprunt";
    }

    // ## Controles des données
    $donnees['idAdherent'] = $_POST["idAdherent"];
    $donnees['noExemplaire'] = htmlentities($_POST["noExemplaire"]);
    $donnees['dateEmprunt'] = $_POST["dateEmprunt"];

    if ($idAdherent == 0){
      $erreurs['idAdherent']="Selectionnez un adhérent";
    }

    if (! preg_match("#^([0-9]{1,2})[-/]([0-9]{1,2})[-/]([0-9]{4})$#", $donnees['dateEmprunt'], $matches)) {
      $erreurs['dateEmprunt'] = "La date doit être au format JJ/MM/AAAA ou bien JJ-MM-AAAA";
      echo $erreurs['dateEmprunt'];
    }else {
      if (! checkdate($matches[2], $matches[1], $matches[3])) {
        $erreurs['dateEmprunt'] = "La date n'est pas valide";
        echo $erreurs['dateEmprunt'];
      }else {
        $donnees['dateEmprunt_us'] = $matches[3]."-".$matches[2]."-".$matches[1];
      }
    }


    if (empty($erreurs)) {

      // ## accès au modèle
      $ma_requete_SQL = "INSERT INTO EMPRUNT(idAdherent, noExemplaire, dateEmprunt)
      VALUES ('" . $donnees['idAdherent'] . "','" . $donnees['noExemplaire'] . "','" . $donnees['dateEmprunt_us'] . "');";
      $bdd->exec($ma_requete_SQL);

      // ## Redirection
      header("Location: Emprunt_show.php");
    }
}


?>

<form method="post" action="Emprunt_add.php">
  <div class="row">
    <fieldset>
      <legend>Ajouter un emprunt</legend>
      <?php
      if (isset($erreurs['nombreEmprunt'])): ?>
      <div class="erreurNombreEmprunt">
        <p id="texteErreurNombreEmprunt"><h4>Ajout impossible: </h4><?php echo $erreurs['nombreEmprunt'] ?></p>
      </div>
      <?php
    endif;
    ?>
    <label>Adherent :
      <select name="idAdherent">
        <option value="0" >Selectionnez un adhérent:</option>

        <?php
        foreach ($donneesAdherent as $row): ?>
        <option value="<?php echo $row['idAdherent']?>"
          <?php
          if (isset($donnees['idAdherent'])){
            if ($row['idAdherent']==$donnees['idAdherent']) { ?>
              selected
            <?php }
          }
          ?>
          >
          <?php echo $row['nomAdherent']; ?>
        </option>
      <?php endforeach; ?>
    </select>
    <?php if (isset($erreurs['idAdherent']))
    echo '<div class="alertdanger">'.$erreurs['idAdherent'].'</div>';
    ?>

  </label>
  <label>Exemplaire disponibles
    <select name="noExemplaire">
      <?php
      foreach ($donneesExemplaire as $row2): ?>
      <option value="<?php echo $row2['noExemplaire']?>"
        <?php
        if (isset($donnees['noExemplaire'])){
          if ($row2['noExemplaire']==$donnees['noExemplaire']) { ?>
            selected
          <?php }
        }
        ?>
        >
        <?php echo $row2['noExemplaire']." - ".$row2['titre']?>
      </option>
    <?php endforeach; ?>
  </select>
</label>
<label > Date Emprunt</label>
<input type="text" name="dateEmprunt" value="<?php if(isset($donnees['dateEmprunt'])) echo $donnees['dateEmprunt'];?>">
<?php if (isset($erreurs['dateEmprunt']))
echo '<div class="alertdanger">'.$erreurs['dateEmprunt'].'</div>';
?>

<input name="addEmprunt" type="submit" value="Ajouter un emprunt">
</fieldset>
</div>
</form> -->




<?php
include("v_head.php");
include ("v_nav.php");


$ma_requete_SQLAdherent = "SELECT ADHERENT.nomAdherent, ADHERENT.idAdherent
                            FROM ADHERENT ;";
$reponseAdherent = $bdd->query($ma_requete_SQLAdherent);
$donneesAdherent = $reponseAdherent->fetchAll();

$ma_requete_SQLExemplaire = "SELECT EXEMPLAIRE.noExemplaire, EXEMPLAIRE.noOeuvre, OEUVRE.titre
                            FROM EXEMPLAIRE
                            INNER JOIN OEUVRE ON EXEMPLAIRE.noOeuvre = OEUVRE.noOeuvre
                            WHERE EXEMPLAIRE.noExemplaire NOT IN (SELECT EMPRUNT.noExemplaire FROM EMPRUNT WHERE (dateRendu IS NULL OR dateRendu='0000-00-00') );";
$reponseExemplaire = $bdd->query($ma_requete_SQLExemplaire);
$donneesExemplaire = $reponseExemplaire->fetchAll();

if (isset($_POST['idAdherent'])) {
    $id = $_POST['idAdherent'];
    $ma_requete_SQL = "SELECT emp.dateEmprunt , emp.dateRendu, ex.noExemplaire, ad.idAdherent,ad.nomAdherent, ex.etat, oe.noOeuvre , oe.titre
                    FROM  EMPRUNT emp
                    INNER JOIN EXEMPLAIRE ex ON ex.noExemplaire = emp.noExemplaire
                    INNER JOIN OEUVRE oe ON oe.noOeuvre = ex.noOeuvre
                    INNER JOIN ADHERENT ad ON ad.idAdherent = emp.idAdherent
                    WHERE emp.idAdherent = $id
                    AND (dateRendu = '0000-00-00' OR dateRendu IS NULL);";
    $reponse = $bdd->query($ma_requete_SQL);
    $donnees = $reponse->fetchAll();


    // RECUPERER LE NOM DE L'adhérent
    $getNomAdherent = "SELECT nomAdherent FROM ADHERENT WHERE idAdherent =".$_POST['idAdherent'].";";
    $reponse = $bdd->query($ma_requete_SQL);
    $nomAdherent = $reponse->fetch();


}

// Traitement:
if (isset($_POST["idAdherent"]) AND isset($_POST["noExemplaire"])){

    $idAdherent = $_POST["idAdherent"];
    $ma_requete_SQLNombreEmprunt = "SELECT COUNT(emp.noExemplaire) AS nombreEmprunt, ad.idAdherent
                                    FROM EMPRUNT emp
                                    INNER JOIN EXEMPLAIRE ex ON ex.noExemplaire = emp.noExemplaire
                                    INNER JOIN OEUVRE oe ON oe.noOeuvre = ex.noOeuvre
                                    INNER JOIN ADHERENT ad ON ad.idAdherent = emp.idAdherent
                                    WHERE emp.idAdherent =".$idAdherent." and (emp.dateRendu IS NULL OR dateRendu='0000-00-00');";
    $reponseNombreEmprunt = $bdd->query($ma_requete_SQLNombreEmprunt);
    $donneesNombreEmprunt = $reponseNombreEmprunt->fetch();

    // Limitation de 5 emprunts
    if ($donneesNombreEmprunt['nombreEmprunt']>=5){
        $erreurs['nombreEmprunt'] = "Cet adhérent possède 5 livres en cours d'emprunt";
    }

    // ## Controles des données
    $donnees['idAdherent'] = $_POST["idAdherent"];
    $donnees['noExemplaire'] = htmlentities($_POST["noExemplaire"]);
    $donnees['dateEmprunt'] = $_POST["dateEmprunt"];

    if ($idAdherent == 0){
      $erreurs['idAdherent']="Selectionnez un adhérent";
    }

    if (! preg_match("#^([0-9]{1,2})[-/]([0-9]{1,2})[-/]([0-9]{4})$#", $donnees['dateEmprunt'], $matches)) {
      $erreurs['dateEmprunt'] = "La date doit être au format JJ/MM/AAAA ou bien JJ-MM-AAAA";
      echo $erreurs['dateEmprunt'];
    }else {
      if (! checkdate($matches[2], $matches[1], $matches[3])) {
        $erreurs['dateEmprunt'] = "La date n'est pas valide";
        echo $erreurs['dateEmprunt'];
      }else {
        $donnees['dateEmprunt_us'] = $matches[3]."-".$matches[2]."-".$matches[1];
      }
    }


    if (empty($erreurs)) {

      // ## accès au modèle
      $ma_requete_SQL = "INSERT INTO EMPRUNT(idAdherent, noExemplaire, dateEmprunt)
      VALUES ('" . $donnees['idAdherent'] . "','" . $donnees['noExemplaire'] . "','" . $donnees['dateEmprunt_us'] . "');";
      $bdd->exec($ma_requete_SQL);

      // ## Redirection
      header("Location: Emprunt_show.php");
    }
}


?>

<form method="post" action="Emprunt_add2.php">
  <div class="row">
    <legend>Choisissez l'adhérent concerné</legend>

    <label>Adhérent</label>
    <select name="idAdherent">
      <?php
      foreach ($donneesAdherent as $rowSelectAdherent): ?>
      <option value="<?php echo $rowSelectAdherent['idAdherent']?>"><?php echo $rowSelectAdherent['nomAdherent']?></option>
    <?php endforeach; ?>
  </select>

  <input type="submit" value="Valider" name="Valider">
</div>
</form>

<?php
if (isset($_POST['idAdherent'])) {?>
  <div class="tableauEmpruntExemplaire"></div>
  <table border="2">
    <caption> Emprunt en cours pour <?php echo $nomAdherent['nomAdherent'] ?> </caption>
      <thead>
        <tr>
          <td>Titre du livre</td>
          <td>Date Emprunt</td>
          <td>Etat</td>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($donnees as $row): ?>
          <tr>
            <div class="row">
              <td><?php echo $row['titre'] ?></td>
              <td><?php echo $row['dateEmprunt'] ?></td>
              <td><?php echo $row['etat']?></td>
            </div>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <form method="post" action="Emprunt_add2.php">
      <div class="row">
        <fieldset>

          <?php
          if (isset($erreurs['nombreEmprunt'])): ?>
          <div class="erreurNombreEmprunt">
            <p id="texteErreurNombreEmprunt"><h4>Ajout impossible: </h4><?php echo $erreurs['nombreEmprunt'] ?></p>
          </div>
          <?php
        endif;
        ?>
        <label>Exemplaire disponibles
          <select name="noExemplaire">
            <?php
            foreach ($donneesExemplaire as $row2): ?>
            <option value="<?php echo $row2['noExemplaire']?>"
              <?php
              if (isset($donnees['noExemplaire'])){
                if ($row2['noExemplaire']==$donnees['noExemplaire']) { ?>
                  selected
                <?php }
              }
              ?>
              >
              <?php echo $row2['noExemplaire']." - ".$row2['titre']?>
            </option>
          <?php endforeach; ?>
        </select>
      </label>

      <label> Date Emprunt</label>
      <input type="text" name="dateEmprunt" value="<?php if(isset($donnees['dateEmprunt'])) echo $donnees['dateEmprunt'];?>">
      <?php if (isset($erreurs['dateEmprunt']))
      echo '<div class="alertdanger">'.$erreurs['dateEmprunt'].'</div>';
      ?>

      <input type="hidden" name="idAdherent" value="<?php echo $row['idAdherent'] ?>">
      <input type="hidden" name="noExemplaire" value="<?php echo $row2['noExemplaire'] ?>">
      <input type="hidden" name="dateEmprunt" value="<?php echo $donnees['dateEmprunt'] ?>">
      <input name="addEmprunt" type="submit" value="Ajouter un emprunt">
    </fieldset>
  </div>
</form>

  <?php } ?>
