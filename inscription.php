<?php include('parts/header.php'); //on inclus le header?>
<?php

if(isset($_SESSION['id'])){
    header('Location: profil');
    exit;
}
include('php/inscriptionData.php'); //on inclus le code d'inscription
?>
<form method="post" action="" class="connexion">
	<div class="teteConnexion">
		<h3>Inscription</h3>
		<p>Commencez à utiliser le site avec votre espace personnel</p>
	</div>
	<?php
	if (isset($erreur)){
		echo "<div class=\"erreur\">". $erreur . "</div>";
	}

	if (isset($valide)){
		echo "<div class=\"valide\">". $valide . "</div>";
	}
	?>
	<div class="groupeConnexion">
		<label class="logoConnexion"><i class="fas fa-user-astronaut"></i>
		<input class="parametreConnexion" type="text" placeholder="Votre nom d'utilisateur" id="pseudo" name="pseudo" value="<?php if(isset($pseudo)) { echo $pseudo; } ?>" required>
		</label>
	</div>

	<div class="groupeConnexion">
		<label class="logoConnexion"><i class="fas fa-envelope"></i>
		<input class="parametreConnexion" type="email" placeholder="Votre email" id="email" name="email" value="<?php if(isset($email)) { echo $email; } ?>" required>
		</label>
	</div>

	<div class="groupeConnexion">
		<input class="parametreConnexion" type="email" placeholder="Confirmez votre email" id="email2" name="email2" value="<?php if(isset($email2)) { echo $email2; } ?>" required>
	</div>

	<div class="groupeConnexion">
		<label class="logoConnexion"><i class="fas fa-lock"></i>
		<input class="parametreConnexion" type="password" placeholder="Votre mot de passe" id="mdp" name="mdp" required>
		</label>
	</div>

	<div class="groupeConnexion">
		<input class="parametreConnexion" type="password" placeholder="Confirmez votre mot de passe" id="mdp2" name="mdp2" required>
	</div>

	<button class="boutonConnexion" type="submit" name="forminscription">Créer mon compte</button>
	<div class="piedConnexion">
		Déjà des nôtres ? <a href="connexion">Se connecter</a>
	</div>
</form>

<script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>
<?php include('parts/footer.php'); //on inclus le footer?>
