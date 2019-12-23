<?php include('parts/header.php'); //on inclus le header?>
<?php
// S'il n'y a pas de session alors on ne va pas sur cette page
if(!isset($_SESSION['id'])){
	header('Location: index');
	exit;
}
// On récupère les informations de l'utilisateur connecté
$afficher_profil = $DB->query("SELECT * FROM users WHERE id = ?", array($_SESSION['id']));
$afficher_profil = $afficher_profil->fetch();

?>
<h2>Voici votre profil, <?= $afficher_profil['username']; ?></h2>
	<div>Informations de compte:</div>
	<ul>
		<li>Votre email est : <?= $afficher_profil['email'] ?></li>
		<li>Votre compte a été créé le : <?= $afficher_profil['date_inscription'] ?></li>
	</ul>
<?php include('parts/footer.php'); ?>
