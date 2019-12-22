<?php include('parts/header.php'); //on inclus le header?>
<?php
  if (isset($_SESSION['id'])){
    header('Location: index.php');
    exit;
  }

  if(!empty($_POST)){
    extract($_POST);
    $valid = true;

    if (isset($_POST['oublie'])){
      $mail = htmlentities(strtolower(trim($mail))); // On récupère le mail afin d envoyer le mail pour la récupèration du mot de passe 

      // Si le mail est vide alors on ne traite pas
      if(empty($mail)){
        $valid = false;
        $er_mail = "Il faut mettre un mail";
      }

      if($valid){
        $req = $DB->query("SELECT * FROM users WHERE email = ?", array($mail));
        $req = $req->fetch();

        if(isset($req['email'])){
          if($req['reset_pass'] == 0){
            // On génère un mot de passe à l'aide de la fonction RAND de PHP
            $new_pass = rand();
            // Le mieux serait de générer un nombre aléatoire entre 7 et 10 caractères (Lettres et chiffres)
            $new_pass_crypt = crypt($new_pass, '$6$rounds=5000$szgrzgerggegehrhhfshsh156s1@tfhs6h146-6GRS6G4¨^drfg4dg$');
            // $new_pass_crypt = crypt($new_pass, "VOTRE CLÉ UNIQUE DE CRYPTAGE DU MOT DE PASSE");

            $objet = 'Nouveau mot de passe';
            $to = $req['email'];

            //===== Création du header du mail.
            $header = "From: Ceans de voyages.com <ceans@voyages.com> \n";
            $header .= "Reply-To: ".$to."\n";
            $header .= "MIME-version: 1.0\n";
            $header .= "Content-type: text/html; charset=utf-8\n";
            $header .= "Content-Transfer-Encoding: 8bit";

            //===== Contenu de votre message
            $message = '<html><body>';
            $message .= "<h1 style='text-align: center'>Réinitialisation de votre mot de passe</h1>";
            $message .= "<p style='text-align: center; font-size: 18px'><b>Bonjour " .$req['username']."</b>,</p><br/>";
            $message .= "<p style='text-align: center'><i><b>Votre nouveau mot de passe est : </b></i>".$new_pass."</p><br/>";
            $message .= "<p style='text-align: center'>Je vous conseille vivement de le changer à votre prochaine connexion !</p><br/>";
            $message .= '</body></html>';
            //===== Envoi du mail
            mail($to, $objet, $message, $header);

            $DB->insert("UPDATE users SET password = ?, reset_pass = 1 WHERE email = ?", array($new_pass_crypt, $req['email']));
          }
        }
        header('Location: connexion.php');
        exit;
      }
    }
  }
?>
        <div>Mot de passe oublié</div>
        <form method="post">
            <?php if (isset($er_mail)){ ?>
                <div><?= $er_mail ?></div>
            <?php } ?>
            <input type="email" placeholder="Adresse mail" name="mail" value="<?php if(isset($mail)){ echo $mail; }?>" required>
            <button type="submit" name="oublie">Envoyer</button>
        </form>
<?php include('parts/footer.php'); //on inclus le footer?>
