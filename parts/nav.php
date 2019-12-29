<nav>
    <ul class="menu">
        <li class="item"><a href="index">Acceuil</a></li>
		<li class="item"><a href="actualite">Actualité</a></li>
		<?php
			if (!isset($_SESSION['id'])) {
		?>
        <li class="item button"><a href="connexion">Connexion</a></li>
		<li class="item button secondary"><a href="inscription">Inscription</a></li>
		<?php
			} else {
		?>
		<li class="item button"><a href="profil">Profil</a></li>
		<li class="item button secondary"><a href="deconnexion">Déconnexion</a></li>
		<?php
			}
		?>
        <li class="toggle"><a href=""><i class="fas fa-bars"></i></a></li>
    </ul>
</nav>
