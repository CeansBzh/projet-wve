<?php include('parts/header.php'); //on inclus le header?>
<?php

  // S'il y a une session alors on ne retourne plus sur cette page
  if (isset($_SESSION['id'])){
    header('Location: index.php');
    exit;
  }

  // Si la variable "$_Post" contient des informations alors on les traitres
  if(!empty($_POST)){
    extract($_POST);
    $valid = true;

    if (isset($_POST['connexion'])){
      $mail = htmlentities(strtolower(trim($mail)));
      $mdp = trim($mdp);

      if(empty($mail)){ // Vérification qu'il y est bien un mail de renseigné
        $valid = false;
        $er_mail = "Il faut mettre un mail";
      }
      if(empty($mdp)){ // Vérification qu'il y est bien un mot de passe de renseigné
        $valid = false;
        $er_mdp = "Il faut mettre un mot de passe";
      }
        // On fait une requête pour savoir si le couple mail / mot de passe existe bien car le mail est unique !
        $req = $DB->query("SELECT * FROM users WHERE email = ? AND password = ?", array($mail, crypt($mdp, '$6$rounds=5000$szgrzgerggegehrhhfshsh156s1@tfhs6h146-6GRS6G4¨^drfg4dg$')));
        $req = $req->fetch();

          // Si on a pas de résultat alors c'est qu'il n'y a pas d'utilisateur correspondant au couple mail / mot de passe
          if ($req['id'] == ""){
            $valid = false;
            $er_mail = "Le mail ou le mot de passe est incorrecte";
					}elseif($req['confirmation_token'] == 0){ // On remet à zéro la demande de nouveau mot de passe s'il y a bien un couple mail / mot de passe
						$valid = false;
            $er_mail = "Veuillez confirmer votre compte à partir de l'email qui vous a été envoyé";
          }elseif($req['reset_pass'] == 1){ // On remet à zéro la demande de nouveau mot de passe s'il y a bien un couple mail / mot de passe
            $DB->insert("UPDATE users SET reset_pass = 0 WHERE id = ?", array($req['id']));
          }
            // S'il y a un résultat alors on va charger la SESSION de l'utilisateur en utilisateur les variables $_SESSION
            if ($valid){
              $_SESSION['id'] = $req['id']; // id de l'utilisateur unique pour les requêtes futures
              $_SESSION['username'] = $req['username'];
              $_SESSION['mail'] = $req['mail'];

              header('Location:  index.php');
              exit;
            }
        }
    }
?>
        <div>Se connecter</div>
        <form method="post">
          <?php
          if (isset($er_mail)){
            ?>
            <div><?= $er_mail ?></div>
            <?php
            }
          ?>

            <input type="email" placeholder="Adresse mail" name="mail" value="<?php if(isset($mail)){ echo $mail; }?>" required>

            <?php
                if (isset($er_mdp)){
            ?>
                <div><?= $er_mdp ?></div>
            <?php
                }
            ?>

            <input type="password" placeholder="Mot de passe" name="mdp" value="<?php if(isset($mdp)){ echo $mdp; }?>" required>

            <button type="submit" name="connexion">Se connecter</button>

        </form>
<?php include('parts/footer.php'); //on inclus le footer?>
