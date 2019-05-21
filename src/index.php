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

<div class="contenu">

    <div class="row">
        <div class="title-index">
            Bienvenue sur l'interface de gestion de bibliothèque
        </div>
    </div>


</div>

<?php include ("v_foot.php");  ?>