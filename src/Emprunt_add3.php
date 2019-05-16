<?php

include('v_head.php');
include('v_nav.php');

include('connexion_bdd.php');


$selec = false;

$requete_adherent = "SELECT idAdherent, nomAdherent FROM ADHERENT;";
$reponse_adherent = $bdd->query($requete_adherent);
$donnees_adherent = $reponse_adherent->fetchAll();


$requete3 ="SELECT OEUVRE.titre, OEUVRE.noOeuvre
            FROM OEUVRE
            JOIN EXEMPLAIRE ON EXEMPLAIRE.noOeuvre = OEUVRE.noOeuvre
            HAVING COUNT(EXEMPLAIRE.noOeuvre) > 0;";
$reponse3 = $bdd->query($requete3);
$donnees_auteur2 = $reponse3->fetchAll();


if (isset($_POST['noExemplaire']) && isset($_POST['dateEmprunt']) && isset($_POST['idAdherent']) ) {

  $_SESSION["heure"] = date('H');
  $_SESSION["minute"] = date('i');
  $_SESSION["seconde"] = date('s');
  $_SESSION["insertion"] = 0;
  $idAdherent = $_POST['idAdherent'];
  $dateEmprunt = $_POST['dateEmprunt'];
  // $dateRendu = $_POST['dateRendu'];
  $noExemplaire = $_POST['noExemplaire'];

  $sql = "INSERT INTO EMPRUNT (noExemplaire,dateEmprunt, idAdherent)
          VALUES ($noExemplaire, '$dateEmprunt', $idAdherent)";
  $pos = strpos($sql,'#');

  if($pos == false){
        $res = $bdd->exec($sql);
        if ($res == 1) {
        $_SESSION["insertion"] = 1;
        }
      } else{
        $_SESSION["insertion"] = 0;
    }
  header("Location: Emprunt_show.php");

}

if (isset($_POST['Adherent'])){
  $idAdherent = $_POST['Adherent'];
  $selec = true;

  $requete_exemplaire ="SELECT EXEMPLAIRE.noExemplaire, EXEMPLAIRE.noOeuvre, EMPRUNT.idAdherent, ADHERENT.nomAdherent, OEUVRE.titre, EMPRUNT.dateEmprunt, EMPRUNT.dateRendu,
                        DATEDIFF(DATE(NOW()), DATE_ADD(EMPRUNT.dateEmprunt, INTERVAL 30 DAY)) AS retard
                        FROM EXEMPLAIRE
                        JOIN OEUVRE ON OEUVRE.noOeuvre = EXEMPLAIRE.noOeuvre
                        JOIN EMPRUNT ON EMPRUNT.noExemplaire = EXEMPLAIRE.noExemplaire
                        INNER JOIN ADHERENT ON ADHERENT.idAdherent = EMPRUNT.idAdherent
                        WHERE EXEMPLAIRE.noExemplaire NOT IN ( SELECT EMPRUNT.noExemplaire FROM EMPRUNT WHERE EMPRUNT.daterendu IS NULL OR EMPRUNT.dateRendu = '0000-00-00' );";
  $reponse_exemplaire = $bdd->query($requete_exemplaire);
  $donnees_exemplaire = $reponse_exemplaire->fetchAll();

}


 ?>

<div class="container">
  <div class="row">
    <div class="col-md-6">
      <?php $sql ?>
      <?php if($selec == false): ?>
        <form class="form" action="Emprunt_add3.php" method="post">

          <div class="form-group">
            <label> Adhérent </label>
            <select class="form-control" type="text" name="Adherent">
            <?php foreach ($donnees_adherent as $value): ?>
              <option value="<?php echo $value['idAdherent'];?>"><?php echo $value['nomAdherent'];?></option>
            <?php endforeach; ?>
            </select>
            <button class="btn btn-lg btn-primary btn-block" type="submit" name="valide" style="width:20%; margin-top:40%; margin: auto">valider</button>
          </div>

        </form>
        <?php endif; ?>
          <?php if($selec == true): ?>
            <form class="form" action="Emprunt_add3.php" method="post">

              <div class="form-group">
                <label>Exemplaire</label>
                <?php $sql ?>
              <select class="form-control" type="text" name="noExemplaire">
              <?php foreach ($donnees_exemplaire as $value): ?>
                <option value="<?php echo $value['noExemplaire'];?>"><?php echo $value['noExemplaire']." - ".$value['titre'];?></option>
              <?php endforeach; ?>
              </select>
            </div>
          <div class="form-group">
            <label>Date de Emprunt</label>
            <input class="form-control" type="date" name="dateEmprunt" placeholder="aaaa/mm/jj" value="<?= date('Y-m-d')?>">
          </div>

          <input type="hidden" name="idAdherent" value="<?php echo $idAdherent ?>">

          <button class="btn btn-lg btn-primary btn-block" type="submit" name="valide" style="width:52%; margin: auto">Ajouter</button>
        </form>

        <div class="tableauRendre"></div>
        <table border="2">
          <caption> Récapitulatifs des Emprunts </caption>
            <thead>
              <tr>
                <td> Titre du livre </td>
                <td> Date Emprunt</td>
                <td> Nombres de jours</td>
                <td> Exemplaire </td>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($donnees_exemplaire as $row): ?>
                <tr>
                  <form method="post" action="Emprunt_return.php">
                    <div class="row">
                      <td><?php echo $row['titre'] ?></td>
                      <td><?php echo date("d/m/Y", strtotime($row['dateEmprunt']))?></td>
                      <td><?php echo $row['retard']?></td>
                      <td><?php echo $row['noExemplaire']; ?>
                      <!-- <td>
                        <input type="hidden" name="idAdherent" value="<?php echo $row['idAdherent'] ?>">
                        <input type="hidden" name="noExemplaire" value="<?php echo $row['noExemplaire'] ?>">
                        <input type="hidden" name="dateEmprunt" value="<?php echo $row['dateEmprunt'] ?>">
                        <input type="submit" value="Rendre" name="Rendre">
                      </td> -->
                    </div>
                  </form>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>

      <?php endif; ?>
    </div>
  </div>
</div>
