<?php
	session_start();
	include('connexionDB.php');

?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="description" content="Voyage est un site d'organisation de vacances, où l'utilisateur peut ajouter des étapes à son voyage ainsi que des photos.">
	<title>Voyages - Carnet de bord</title>
	<link rel="icon" type="image/png" href="images/logo.png" />
	<link rel="stylesheet" href="style.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css" crossorigin="anonymous">
</head>
<body>
	<header>
		<h1 class="logo"><a href="index">Voyages</a></h1>
	</header>
	<main>
			<?php include('parts/nav.php'); ?>
