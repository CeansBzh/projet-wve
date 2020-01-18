<?php
	session_start();
	include('php/connexionDB.php');

?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="description" content="Voyages est un créateur de carnets de voyages. Utilisez cette application afin d'organiser vos vacances en créant des nouveaux voyages et en ajoutant des étapes fournies en textes et photos. Puis partagez-le !">
	<!-- <base href="<?= $url ?>" /> -->
	<title>Voyages - Carnet de bord</title>
	<link rel="icon" type="image/png" href="images/logo.png" />
	<link rel="stylesheet" href="style.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
</head>
<body>
	<noscript>
      <div style="text-align: center; border: 3px solid red; padding: 10px">
        <span style="color:red; font-weight: 700;">Il semblerait que vous bloquiez le Javascript :/</span>
      </div>
    </noscript>
	<header>
		<h1 class="logo"><a href="<?php echo $url ?>">Voyages</a></h1>
	</header>
	<main>
			<?php include('parts/nav.php'); ?>
