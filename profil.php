<?php include('parts/header.php'); //on inclus le header?>
<?php
// S'il n'y a pas de session alors on ne va pas sur cette page
if(!isset($_SESSION['id'])){
	header('Location: connexion');
	exit;
}
// On récupère les informations de l'utilisateur connecté
$req = $DB->query("SELECT * FROM users WHERE id = ?", array($_SESSION['id']));
$req = $req->fetch();

?>
<h2>Voici votre profil, <?= $req['username']; ?></h2>
	<div>Informations de compte:</div>
	<ul>
		<li>Votre email est : <?= $req['email'] ?></li>
		<li>Votre compte a été créé le : <?= $req['date_inscription'] ?></li>
	</ul>
<?php include('parts/footer.php'); ?>
