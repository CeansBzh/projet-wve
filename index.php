<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>Voyages - Carnet de bord</title>
	<link rel="stylesheet" href="style.css" />
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>
<body>
	<header>
		<h1>Voyages</h1>
		<h2>Racontez-vos voyages pendant que vous y êtes</h2>
	</header>
	<div class="corps">
		<?php
		if (!isset($_SESSION['id'])) {
		    echo "<a href=\"inscription.php\">Inscription</a> <!-- Liens de nos futures pages -->
							<a href=\"connexion.php\">Connexion</a>
							<a href=\"motdepasse.php\">Mot de passe oublié</a>";
		} else {
			echo "<a href=\"profil.php\">Mon profil</a>
						<a href=\"modifier-profil.php\">Modifier mon profil</a>
						<a href=\"deconnexion.php\">Déconnexion</a>";
		}
		?>
	</div>
</body>
</html>
