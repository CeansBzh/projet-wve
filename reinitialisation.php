<?php include('parts/header.php'); //on inclus le header?>
<?php
  if (isset($_SESSION['id'])){
    header('Location: index');
    exit;
  }

  if(!empty($_POST)){
    extract($_POST);
    $valid = true;

    if (isset($_POST['oublie'])){
      $email = htmlentities(strtolower(trim($email))); // On récupère le email afin d envoyer le email pour la récupèration du mot de passe 

      // Si le email est vide alors on ne traite pas
      if(empty($email)){
        $valid = false;
        $er_mail = "Il faut mettre un email";
      }

      if($valid){
        $req = $DB->query("SELECT * FROM users WHERE email = ?", array($email));
        $req = $req->fetch();

        if(isset($req['email'])){
          if($req['reset_pass'] == 0){
            // On génère un mot de passe à l'aide de la fonction RAND de PHP
            $new_pass = rand();
            // Le mieux serait de générer un nombre aléatoire entre 7 et 10 caractères (Lettres et chiffres)
            $new_pass_crypt = crypt($new_pass, '$6$rounds=5000$' . $clesecrete);
            // $new_pass_crypt = crypt($new_pass, "VOTRE CLÉ UNIQUE DE CRYPTAGE DU MOT DE PASSE");

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
            $sendSmtpEmail['templateId'] = 5;
            $sendSmtpEmail['params'] = array('NAME'=>$req['username'], 'URL'=>$url . '/conf?id=' . $req['id'] . '&token=' . $token . '');
            $sendSmtpEmail['headers'] = array('X-Mailin-custom'=>'MIME-version:1.0|Content-type:text/html; charset=utf-8');
            
            try {
              $result = $apiInstance->sendTransacEmail($sendSmtpEmail);
            } catch (Exception $e) {
              echo 'Exception when calling SMTPApi->sendTransacEmail: ', $e->getMessage(), PHP_EOL;
            }

            $DB->insert("UPDATE users SET password = ?, reset_pass = 1 WHERE email = ?", array($new_pass_crypt, $req['email']));
          }
        }
        header('Location: connexion');
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
    <input type="email" placeholder="Adresse email" name="email" value="<?php if(isset($email)){ echo $email; }?>" required>
    <button type="submit" name="oublie">Envoyer</button>
  </form>
<?php include('parts/footer.php'); //on inclus le footer?>
