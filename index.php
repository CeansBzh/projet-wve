<?php include('parts/header.php'); ?>
		<?php
		if (!isset($_SESSION['id'])) {
		    echo "<a href=\"inscription.php\">Inscription</a> <!-- Liens de nos futures pages -->
							<a href=\"connexion.php\">Connexion</a>
							<a href=\"reinitialisation.php\">Mot de passe oublié</a>";
		} else {
			echo "<a href=\"profil.php\">Mon profil</a>
						<a href=\"modifier-profil.php\">Modifier mon profil</a>
						<a href=\"deconnexion.php\">Déconnexion</a>";
		}
		?>
<?php include('parts/footer.php'); ?>
