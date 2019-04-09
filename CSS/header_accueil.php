<!-- header -->
<?php if(isset($_GET['partie'])){
  $partie = $_GET['partie'];
}
else{
  $partie = 0;
} ?>

<div class="header_tech">
	<header class="row">
		<div class="col-sm-12 banner">
			<img class="banner" src="img/banner.png" alt="image barre"/>
		</div>

	</header>
</div>
<div class="d-block d-md-none row">
  <nav class="navigation_partie">
    <ul id="menu-accordeon">
      <li><a href="#">Menu des parties</a>
        <ul>
          <li class="histoire"><a href="index.php?partie=1"><i class="fa fa-book-open"></i><span>Histoire</span></a></li>
          <li class="poids"><a href="index.php?partie=2"><i class="fa fa-balance-scale"></i><span>Le poids dans la société</span></a></li>
          <li class="danger"><a href="index.php?partie=3"><i class="fa fa-exclamation-triangle"></i><span>Les dangers et limites</span></a></li>
          <li class="metier"><a href="index.php?partie=4"><i class="fa fa-user-tie"></i><span>Le métier de youtubeur</span></a></li>
          <li class="eco"><a href="index.php?partie=5"><i class="fa fa-dollar-sign"></i><span>Modèle économique de YouTube</span></a></li>
          <li class="orga"><a href="index.php?partie=6"><i class="fa fa-sitemap"></i><span>Organisation de l'entreprise YouTube</span></a></li>
          <li class="tech"><a href="page.php"><i class="fa fa-wrench "></i><span>Partie technique</span></a></li>
          <li class="contact"><a href="page.php?article=4"><i class="fa fa-comments "></i><span>Contactez-nous</span></a></li>
        </ul>
      </li>
    </ul>
  </nav>
</div>
<div class="header_accueil">
	<?php
	switch($partie){
    case 0:
    include('include/navigation/nav_presentation.php');
    break;

		case 1:
		include('include/navigation/nav_histoire.php');
		break;

		case 2:
		include('include/navigation/nav_societe.php');
		break;

		case 3:
		include('include/navigation/nav_dangers.php');
		break;

		case 4:
		include('include/navigation/nav_metier.php');
		break;

		case 5:
		include('include/navigation/nav_eco.php');
		break;

    case 6:
		include('include/navigation/nav_orga.php');
		break;
	}
	?>
</div>
<?php
switch($partie){
  case 0:
  include('include/navigation/mini_nav_presentation.php');
  break;

  case 1:
  include('include/navigation/mini_nav_histoire.php');
  break;

  case 2:
  include('include/navigation/mini_nav_societe.php');
  break;

  case 3:
  include('include/navigation/mini_nav_dangers.php');
  break;

  case 4:
  include('include/navigation/mini_nav_metier.php');
  break;

  case 5:
  include('include/navigation/mini_nav_eco.php');
  break;

  case 6:
  include('include/navigation/mini_nav_orga.php');
  break;
}
?>
