<nav>
	<ul>
		<?php
			if (!isset($_SESSION['id'])) {
		?>
			<li><a class="navigation active" href="index">Acceuil</a></li>
			<li><a class="navigation" href="actualite">Actualité</a></li>
			<li class="droite"><a class="navigation" href="inscription">Inscription</a></li>
			<li class="droite"><a class="navigation" href="connexion">Connexion</a></li>
		<?php
			} else {
		?>
			<li><a class="navigation active" href="index">Acceuil</a></li>
			<li><a class="navigation" href="actualite">Actualité</a></li>
			<li><a class="navigation" href="profil">Profil</a></li>
			<li class="droite"><a class="navigation" href="deconnexion">Déconnexion</a></li>
		<?php
			}
		?>
	</ul>
</nav>
