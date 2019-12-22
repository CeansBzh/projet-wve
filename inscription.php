<?php
session_start();
include('connexionDB.php');

if(isset($_POST['forminscription'])) {
  $pseudo = htmlspecialchars($_POST['pseudo']);
  $mail = htmlspecialchars($_POST['mail']);
  $mail2 = htmlspecialchars($_POST['mail2']);
  $mdp = htmlspecialchars($_POST['mdp']);
  $mdp2 = htmlspecialchars($_POST['mdp2']);
  if(!empty($_POST['pseudo']) AND !empty($_POST['mail']) AND !empty($_POST['mail2']) AND !empty($_POST['mdp']) AND !empty($_POST['mdp2'])) {
    $valid = true;
    $pseudolength = strlen($pseudo);
    if($pseudolength <= 255) {
     if($mail == $mail2) {
       if(filter_var($mail, FILTER_VALIDATE_EMAIL)){
         // On vérifit que le mail est disponible
         $req_mail = $DB->query("SELECT email FROM users WHERE email = ?", array($mail));
         $req_mail = $req_mail->fetch();
         if ($req_mail['email'] <> ""){
           $valid = false;
           $er_mail = "Ce mail existe déjà";
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
             $DB->insert("INSERT INTO users(username, email, password, date_inscription, token) VALUES(?, ?, ?, ?, ?)", array($pseudo, $mail, $mdp, $date_inscription, $token));
             $erreur = "Votre compte a bien été créé ! <a href=\"connexion.php\">Me connecter</a>";
             //Envoi d'un email de confirmation de compte
             $req = $DB->query("SELECT * FROM users WHERE email = ?", array($mail));
             $req = $req->fetch();
             $mail_to = $req['email'];

             $subject = 'Prêts à raconter vos plus grandes aventures ?';
             $message = '<p>Bonjour ' . $req['username'] . ',</p><br>
﻿  	            <p>Veuillez confirmer votre compte <a href="http://localhost/projet-wve/conf?id=' . $req['id'] . '&token=' . $token . '">Valider</a><p>';
             $headers = array(
                'From' => 'ceans@voyages.com',
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
           $erreur = "Adresse mail déjà utilisée !";
         }
       } else {
         $erreur = "Votre adresse mail n'est pas valide !";
       }
     } else {
       $erreur = "Vos adresses mail ne correspondent pas !";
     }
   } else {
     $erreur = "Votre pseudo ne doit pas dépasser 255 caractères !";
   }
 } else {
   $erreur = "Tous les champs doivent être complétés !";
 }
}
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Inscription</title>
    </head>
    <body>      
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
                     <label for="mail">Mail :</label>
                  </td>
                  <td>
                     <input type="email" placeholder="Votre mail" id="mail" name="mail" value="<?php if(isset($mail)) { echo $mail; } ?>" />
                  </td>
               </tr>
               <tr>
                  <td align="right">
                     <label for="mail2">Confirmation du mail :</label>
                  </td>
                  <td>
                     <input type="email" placeholder="Confirmez votre mail" id="mail2" name="mail2" value="<?php if(isset($mail2)) { echo $mail2; } ?>" />
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
    </body>
</html>
