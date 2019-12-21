<?php
session_start();
include('connexionDB.php');

if(isset($_POST['formconnexion'])) {
  $mailconnect = htmlspecialchars($_POST['mailconnect']);
  $mdpconnect = sha1($_POST['mdpconnect']);
  if(!empty($mailconnect) AND !empty($mdpconnect)) {
    // On vérifit que l'utilisateur existe
    $req_user= $DB->query("SELECT username FROM users WHERE username = ?", array($mailconnect));
    $req_user= $req_user->fetch();
    if ($req_user['username'] <> ""){
      $userexist = 1;
    }
    if($userexist == 1) {
      $userinfo = $requser->fetch();
      $_SESSION['id'] = $userinfo['id'];
      $_SESSION['pseudo'] = $userinfo['pseudo'];
      $_SESSION['mail'] = $userinfo['mail'];
      header("Location: profil.php?id=".$_SESSION['id']);
    } else {
      $erreur = "Mauvais mail ou mot de passe !";
    }
  } else {
    $erreur = "Tous les champs doivent être complétés !";
  }
}
?>
<html>
   <head>
      <title>TUTO PHP</title>
      <meta charset="utf-8">
   </head>
   <body>
      <div align="center">
         <h2>Connexion</h2>
         <br /><br />
         <form method="POST" action="">
            <input type="email" name="mailconnect" placeholder="Mail" />
            <input type="password" name="mdpconnect" placeholder="Mot de passe" />
            <br /><br />
            <input type="submit" name="formconnexion" value="Se connecter !" />
         </form>
         <?php
         if(isset($erreur)) {
            echo '<font color="red">'.$erreur."</font>";
         }
         ?>
      </div>
   </body>
</html>
