<?php include('parts/header.php'); //on inclus le header?>
<?php
	if (!isset($_SESSION['id'])){
		header('Location: connexion');
		exit;
	}

	// On récupère les informations de l'utilisateur connecté
	$afficher_profil = $DB->query("SELECT * FROM users WHERE id = ?", array($_SESSION['id']));
	$afficher_profil = $afficher_profil->fetch();

	if(!empty($_POST)){
		extract($_POST);
		$valid = true;

		if (isset($_POST['modification'])){
			$username = htmlentities(trim($username));
			$email = htmlentities(strtolower(trim($email)));

			if(empty($username)){
				$valid = false;
				$er_nom = "Il faut mettre un nom";
			}

			if(empty($email)){
				$valid = false;
				$er_mail = "Il faut mettre un email";

			}elseif(!preg_match("/^[a-z0-9\-_.]+@[a-z]+\.[a-z]{2,3}$/i", $email)){
				$valid = false;
				$er_mail = "Le email n'est pas valide";

			}else{
				$req_mail = $DB->query("SELECT email FROM users WHERE email = ?", array($email));
				$req_mail = $req_mail->fetch();

				if ($req_mail['email'] <> "" && $_SESSION['email'] != $req_mail['email']){
					$valid = false;
					$er_mail = "Ce email existe déjà";
				}
			}

			if ($valid){

				$DB->insert("UPDATE users SET username = ?, email = ? WHERE id = ?", array($username, $email, $_SESSION['id']));

				$_SESSION['username'] = $username;
				$_SESSION['email'] = $email;

				header('Location: profil');
				exit;
			}
		}
	}
?>
<div>Modification</div>
<form method="post">
	<?php if (isset($er_nom)){ ?>
		<div><?= $er_nom ?></div>
	<?php } ?>
	<input type="text" placeholder="Votre nom d'utilisateur" name="username" value="<?php if(isset($username)){ echo $username; }else{ echo $afficher_profil['username'];}?>" required />

	<?php if (isset($er_mail)){ ?>
		<div><?= $er_mail ?></div>
	<?php } ?>
	<input type="email" placeholder="Adresse email" name="email" value="<?php if(isset($email)){ echo $email; }else{ echo $afficher_profil['email'];}?>" required />

	<button type="submit" name="modification">Modifier</button>
</form>
<?php include('parts/footer.php'); ?>
