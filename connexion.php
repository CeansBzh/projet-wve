<?php include('parts/header.php'); //on inclus le header?>
<?php

  // S'il y a une session alors on ne retourne plus sur cette page
  if (isset($_SESSION['id'])){
    header('Location: index');
    exit;
  }

  // Si la variable "$_Post" contient des informations alors on les traitres
  if(!empty($_POST)){
    extract($_POST);
    $valid = true;

    if (isset($_POST['connexion'])){
      $email = htmlentities(strtolower(trim($email)));
      $mdp = trim($mdp);

      if(empty($email)){ // Vérification qu'il y est bien un email de renseigné
        $valid = false;
        $er_mail = "Sans email, on ne sait pas à qui on a affaire...";
      }
      if(empty($mdp)){ // Vérification qu'il y est bien un mot de passe de renseigné
        $valid = false;
        $er_mdp = "Il manque votre mot de passe ;)";
      }
        // On fait une requête pour savoir si le couple email / mot de passe existe bien car le email est unique !
        $req = $DB->query("SELECT * FROM users WHERE email = ? AND password = ?", array($email, crypt($mdp, '$6$rounds=5000$szgrzgerggegehrhhfshsh156s1@tfhs6h146-6GRS6G4¨^drfg4dg$')));
        $req = $req->fetch();

          // Si on a pas de résultat alors c'est qu'il n'y a pas d'utilisateur correspondant au couple email / mot de passe
          if ($req['id'] == ""){
            $valid = false;
            $er_mail = "Email ou mot de passe incorrect... On ne sait pas lequel :/";
					}elseif($req['confirmation_token'] == 0){ // On remet à zéro la demande de nouveau mot de passe s'il y a bien un couple email / mot de passe
						$valid = false;
            $er_mail = "Veuillez confirmer votre compte grâce au mail qui vous a été envoyé lors de votre inscription.";
          }elseif($req['reset_pass'] == 1){ // On remet à zéro la demande de nouveau mot de passe s'il y a bien un couple email / mot de passe
            $DB->insert("UPDATE users SET reset_pass = 0 WHERE id = ?", array($req['id']));
          }
            // S'il y a un résultat alors on va charger la SESSION de l'utilisateur en utilisateur les variables $_SESSION
            if ($valid){
              $_SESSION['id'] = $req['id']; // id de l'utilisateur unique pour les requêtes futures
              $_SESSION['username'] = $req['username'];
              $_SESSION['email'] = $req['email'];
							$_SESSION['role'] = $req['role'];

              header('Location:  index');
              exit;
            }
        }
    }
?>
<form method="post" class="connexion">
	<div class="teteConnexion">
		<h3>Connexion</h3>
		<p>Accédez à votre espace personnel</p>
	</div>
	<div class="groupeConnexion">
		<?php
		if (isset($er_mail)){
			echo "<div class=\"erreurConnexion\">". $er_mail . "</div>";
		}
		?>
		<label class="logoConnexion"><i class="fas fa-envelope"></i>
		<input class="parametreConnexion" type="email" placeholder="adresse@exemple.fr" name="email" value="<?php if(isset($email)){ echo $email; }?>" required>
		</label>
	</div>
	<div class="groupeConnexion">
		<?php
		if (isset($er_mdp)){
			echo "<div class=\"erreurConnexion\">". $er_mdp . "</div>";
		}
		?>
		<label class="logoConnexion"><i class="fas fa-lock"></i>
		<input class="parametreConnexion" type="password" placeholder="Mot de passe" name="mdp" value="<?php if(isset($mdp)){ echo $mdp; }?>" required>
		</label>
	</div>
	<button class="boutonConnexion" type="submit" name="connexion">Se connecter</button>
	<div class="piedConnexion">
		Pas de compte ? <a href="inscription">S'inscrire</a>
	</div>
</form>
<?php include('parts/footer.php'); //on inclus le footer?>
