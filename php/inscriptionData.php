<?php 
require_once('vendor/autoload.php');

if(basename($_SERVER['PHP_SELF']) === "inscription.php"){
    // S'il y a une session alors on ne retourne plus sur cette page
    if (isset($_SESSION['id'])){
    header('Location: index');
    exit;
    }
}

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
          // On vérifit que le mail est disponible
          $req_mail = $DB->query("SELECT email FROM users WHERE email = ?", array($email));
          $req_mail = $req_mail->fetch();
          if ($req_mail['email'] <> ""){
            $valid = false;
          }
          if($valid != false) {
            // On vérifit que le pseudo est disponible
            $req_pseudo = $DB->query("SELECT username FROM users WHERE username = ?", array($pseudo));
            $req_pseudo = $req_pseudo->fetch();
            if ($req_pseudo['username'] <> ""){
              $valid = false;
            }
            if($valid != false) {
              if($mdp == $mdp2) {
                $mdplength = strlen($mdp);
                if($mdplength >= 8) {
                  $mdp = crypt($mdp, '$6$rounds=5000$' . $clesecrete);
                  $date_inscription = date('Y-m-d H:i:s');
                  // bin2hex(random_bytes($length))
                  $token = bin2hex(random_bytes(12));
                  // Exemples:
                  // 39e9289a5b8328ecc4286da11076748716c41ec7fb94839a689f7dac5cdf5ba8bdc9a9acdc95b95245f80a00
                  // On insert nos données dans la table utilisateur
                  $DB->insert("INSERT INTO users(username, email, password, date_inscription, token) VALUES(?, ?, ?, ?, ?)", array($pseudo, $email, $mdp, $date_inscription, $token));
                  $valide = "Votre compte a bien été créé ! Nous vous avons envoyé un mail de confirmation";
                  //Envoi d'un email de confirmation de compte
                  $req = $DB->query("SELECT * FROM users WHERE email = ?", array($email));
                  $req = $req->fetch();
                  
                  //Sendinblue
                  // Configure API key authorization: api-key
                  $config = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', $sendinblueapi);
                  
                  // Uncomment below line to configure authorization using: partner-key
                  // $config = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('partner-key', 'YOUR_API_KEY');
                  
                  $apiInstance = new SendinBlue\Client\Api\SMTPApi(
                    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
                    // This is optional, `GuzzleHttp\Client` will be used as default.
                    new GuzzleHttp\Client(),
                    $config
                  );
                  $sendSmtpEmail = new \SendinBlue\Client\Model\SendSmtpEmail(); // \SendinBlue\Client\Model\SendSmtpEmail | Values to send a transactional email
                  $sendSmtpEmail['to'] = array(array('email'=>$req['email'], 'name'=>$req['username']));
                  $sendSmtpEmail['templateId'] = 1;
                  $sendSmtpEmail['params'] = array('NAME'=>$req['username'], 'URL'=>$url . '/conf?id=' . $req['id'] . '&token=' . $token . '');
                  $sendSmtpEmail['headers'] = array('X-Mailin-custom'=>'MIME-version:1.0|Content-type:text/html; charset=utf-8');
                  
                  try {
                    $result = $apiInstance->sendTransacEmail($sendSmtpEmail);
                  } catch (Exception $e) {
                    echo 'Exception when calling SMTPApi->sendTransacEmail: ', $e->getMessage(), PHP_EOL;
                  }

                  $_SESSION['id'] = $req['id']; // id de l'utilisateur unique pour les requêtes futures
                  $_SESSION['username'] = $req['username'];
                  $_SESSION['email'] = $req['email'];
                  $_SESSION['role'] = $req['role'];

                  if(basename($_SERVER['PHP_SELF']) === "inscription.php"){
                    header('Location:  index');
                    exit;
                  }else{
                    header('Location: ' . $_SERVER['REQUEST_URI']);
                    exit;
                  }

                } else {
                  $erreur = "Veuillez utiliser un mot de passe d'au moins 8 caractères";
                }
              } else {
                $erreur = "Vos mots de passes ne correspondent pas !";
              }
            } else {
              $erreur = "Ce nom d'utilisateur est déjà utilisé !";
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
} ?>