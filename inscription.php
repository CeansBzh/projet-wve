<?php include('parts/header.php'); //on inclus le header?>
<?php
if(isset($_POST['forminscription'])) {
	$pseudo = htmlspecialchars($_POST['pseudo']);
  $email = htmlspecialchars($_POST['email']);
  $email2 = htmlspecialchars($_POST['email2']);
  $mdp = htmlspecialchars($_POST['mdp']);
  $mdp2 = htmlspecialchars($_POST['mdp2']);
	if(!empty($_POST['pseudo']) AND !empty($_POST['email']) AND !empty($_POST['email2']) AND !empty($_POST['mdp']) AND !empty($_POST['mdp2'])) {
		$valid = true;
    $pseudolength = strlen($pseudo);
    if($pseudolength <= 255) {
			if($email == $email2) {
				if(filter_var($email, FILTER_VALIDATE_EMAIL)){
					// On vérifit que le email est disponible
          $req_mail = $DB->query("SELECT email FROM users WHERE email = ?", array($email));
          $req_mail = $req_mail->fetch();
          if ($req_mail['email'] <> ""){
						$valid = false;
            $er_mail = "Ce email existe déjà";
					}
          if($valid != false) {
						if($mdp == $mdp2) {
              $mdp = crypt($mdp, '$6$rounds=5000$szgrzgerggegehrhhfshsh156s1@tfhs6h146-6GRS6G4¨^drfg4dg$');
              $date_inscription = date('Y-m-d H:i:s');
              // bin2hex(random_bytes($length))
              $token = bin2hex(random_bytes(12));
              // Exemples:
              // 39e9289a5b8328ecc4286da11076748716c41ec7fb94839a689f7dac5cdf5ba8bdc9a9acdc95b95245f80a00
              // On insert nos données dans la table utilisateur
              $DB->insert("INSERT INTO users(username, email, password, date_inscription, token) VALUES(?, ?, ?, ?, ?)", array($pseudo, $email, $mdp, $date_inscription, $token));
              $erreur = "Votre compte a bien été créé ! <a href=\"connexion\">Me connecter</a>";
              //Envoi d'un email de confirmation de compte
              $req = $DB->query("SELECT * FROM users WHERE email = ?", array($email));
              $req = $req->fetch();

              $mail_to = $req['email'];
              $subject = 'Prêts à raconter vos plus grandes aventures ?';
              $message = '<p>Bonjour ' . $req['username'] . ',</p><br>
								<p>Veuillez confirmer votre compte <a href="'. $url . '/conf?id=' . $req['id'] . '&token=' . $token . '">Valider</a><p>';
              $headers = array(
								'From' => 'Ceans de voyages.com <ceans@voyages.com>',
                'Reply-To' => 'ceans@voyages.com',
                'X-Mailer' => 'PHP/' . phpversion(),
                'Content-type' => 'text/html; charset=utf-8',
                'MIME-version' => '1.0',
							);

							mail($mail_to, $subject, $message, $headers);
						} else {
							$erreur = "Vos mots de passes ne correspondent pas !";
						}
					} else {
						$erreur = "Adresse email déjà utilisée !";
					}
				} else {
					$erreur = "Votre adresse email n'est pas valide !";
				}
			} else {
				$erreur = "Vos adresses email ne correspondent pas !";
			}
		} else {
			$erreur = "Votre pseudo ne doit pas dépasser 255 caractères !";
		}
	} else {
		$erreur = "Tous les champs doivent être complétés !";
	}
}
?>
        <div>Inscription</div>
        <form method="POST" action="">
            <table>
               <tr>
                  <td align="right">
                     <label for="pseudo">Pseudo :</label>
                  </td>
                  <td>
                     <input type="text" placeholder="Votre pseudo" id="pseudo" name="pseudo" value="<?php if(isset($pseudo)) { echo $pseudo; } ?>" />
                  </td>
               </tr>
               <tr>
                  <td align="right">
                     <label for="email">email :</label>
                  </td>
                  <td>
                     <input type="email" placeholder="Votre email" id="email" name="email" value="<?php if(isset($email)) { echo $email; } ?>" />
                  </td>
               </tr>
               <tr>
                  <td align="right">
                     <label for="email2">Confirmation du email :</label>
                  </td>
                  <td>
                     <input type="email" placeholder="Confirmez votre email" id="email2" name="email2" value="<?php if(isset($email2)) { echo $email2; } ?>" />
                  </td>
               </tr>
               <tr>
                  <td align="right">
                     <label for="mdp">Mot de passe :</label>
                  </td>
                  <td>
                     <input type="password" placeholder="Votre mot de passe" id="mdp" name="mdp" />
                  </td>
               </tr>
               <tr>
                  <td align="right">
                     <label for="mdp2">Confirmation du mot de passe :</label>
                  </td>
                  <td>
                     <input type="password" placeholder="Confirmez votre mdp" id="mdp2" name="mdp2" />
                  </td>
               </tr>
               <tr>
                  <td></td>
                  <td align="center">
                     <br />
                     <input type="submit" name="forminscription" value="Je m'inscris" />
                  </td>
               </tr>
            </table>
         </form>
         <?php
         if(isset($erreur)) {
            echo '<font color="red">'.$erreur."</font>";
         }
         ?>
<?php include('parts/footer.php'); //on inclus le footer?>
