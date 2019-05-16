<?php
/**
 * Created by PhpStorm.
 * User: tjacob3
 * Date: 26/03/19
 * Time: 18:15
 */?>
<?php
include("connexion_bdd.php");
// traitement
if(isset($_POST)  )  // si il existe certaines variables dans le tableau associatif $_POST
{                    // le formulaire vient d'être soumis

}

// affichage de la vue
?>
<?php include("v_head.php");  ?>
<?php include ("v_nav.php");  ?>

    <div class="row">
        <div class="title-index">
            Bienvenue sur l'interface de gestion de bibliothèque
        </div>
    </div>
    <div class="row">
        <div class="container">
            <div class="formulaires col-sm-6">
                <h4>Vous êtes adhérents à la bibliothèque ?</h4>
                <form class="form-signin">
                    <div class="form-group">
                        <label>Nom</label>
                        <input type="text" name="nom" class="form-control" id="inputName" placeholder="Entrez votre nom">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Mot de passe</label>
                        <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Entrez votre mot de passe">
                    </div>
                    <button type="submit" class="btn btn-primary">Se connecter</button>
                </form>
            </div>
            <div class="formulaires col-sm-6">
                <h4>Vous êtes membre du staff ?</h4>
                <form>
                    <div class="form-group">
                        <label>Nom</label>
                        <input type="name" class="form-control" id="inputName" placeholder="Entrez votre nom">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Mot de passe</label>
                        <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Entrez votre mot de passe">
                    </div>
                    <button type="submit" class="btn btn-primary">Se connecter</button>
                </form>
            </div>
        </div>
    </div>


<?php include ("v_foot.php");  ?>