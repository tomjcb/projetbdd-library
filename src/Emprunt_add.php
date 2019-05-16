<?php include ("connexion_bdd.php");


$ma_requete_sql = "SELECT ADHERENT.nomAdherent, ADHERENT.idAdherent FROM ADHERENT;";
$reponse = $bdd->query($ma_requete_sql);
$data = $reponse ->fetchAll();
?>

<?php include("v_head.php");  ?>
<?php include ("v_nav.php");  ?>

<div class="row">
    <div class="container">
    <form class="col-12">
        <div class="form-group col-12">
            <label for="nom">Nom de l'adhérent :</label>
            <select name="adhérents" class="form-group">
                <?php
                if(isset($data[0])){
                    foreach($data as $values){ ?>
                        <option value="<?php echo $values['idAdherent'] ?>"><?php echo $values['nomAdherent'];?></option>
                    <?php }
                }
                ?>

            </select>
            <input class="btn btn-lg btn-primary" type="submit" name="valider" value="Valider" >
        </div>
    </form>
    </div>
</div>

<?php include ("v_foot.php"); ?>





