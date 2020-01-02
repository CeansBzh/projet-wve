<?php include('parts/header.php'); //on inclus le header?>
<?php include('php/connexionData.php'); //on inclus le code de connexion?>
<form method="post" class="connexion">
	<div class="teteConnexion">
		<h3>Connexion</h3>
		<p>Accédez à votre espace personnel et profitez de toutes les fonctionnalités</p>
	</div>
	<div class="groupeConnexion">
		<?php
		if (isset($er_mail)){
			echo "<div class=\"erreur\">". $er_mail . "</div>";
		}
		?>
		<label class="logoConnexion"><i class="fas fa-envelope"></i>
		<input class="parametreConnexion" type="email" placeholder="adresse@exemple.fr" name="email" value="<?php if(isset($email)){ echo $email; }?>" required>
		</label>
	</div>
	<div class="groupeConnexion">
		<?php
		if (isset($er_mdp)){
			echo "<div class=\"erreur\">". $er_mdp . "</div>";
		}
		?>
		<label class="logoConnexion"><i class="fas fa-lock"></i>
		<input class="parametreConnexion" type="password" placeholder="Mot de passe" name="mdp" value="<?php if(isset($mdp)){ echo $mdp; }?>" required>
		</label>
	</div>
	<button class="boutonConnexion" type="submit" name="connexion">Se connecter</button>
	<div class="piedConnexion">
    <a href="recuperation">Mot de passe oublié ?</a>
	</div>
</form>
<?php include('parts/footer.php'); //on inclus le footer?>
