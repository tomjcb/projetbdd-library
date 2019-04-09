<?php
/**
 * Created by PhpStorm.
 * User: tjacob3
 * Date: 26/03/19
 * Time: 18:36
 */?>

<?php
define("hostname","serveurmysql");
define("database","BDD_tjacob3");
define("username","tjacob3");
define("password","1007");

$dsn = 'mysql:dbname='.database.';host='.hostname.';charset=utf8';

$bdd = new PDO($dsn, username, password);
// pour afficher les erreurs
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// pour récupérer le résultat des requêtes SELECT sous forme de tableaux associatifs
$bdd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);
?>

