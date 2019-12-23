<nav>
	<ul>
		<?php
			if (!isset($_SESSION['id'])) {
		?>
			<li><a href="index">Acceuil</a></li>
			<li><a href="actualite">Actualité</a></li>
			<li class="right"><a href="inscription">Inscription</a></li>
			<li class="right"><a href="connexion">Connexion</a></li>
		<?php
			} else {
		?>
			<li><a href="index">Acceuil</a></li>
			<li><a href="actualite">Actualité</a></li>
			<li><a href="profil">Profil</a></li>
			<li class="right"><a href="deconnexion">Déconnexion</a></li>
		<?php
			}
		?>
	</ul>
</nav>
