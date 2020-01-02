<?php include('parts/header.php'); //on inclus le header?>
<?php
  require_once('vendor/autoload.php');

  if (isset($_SESSION['id'])){
    header('Location: index');
    exit;
  }
  
  if (!isset($_GET['token']) AND !isset($_GET['section'])){

    if(!empty($_POST)){
      extract($_POST);
      $valid = true;
  
      if (isset($_POST['oublie'])){
        $email = htmlentities(strtolower(trim($email))); // On récupère le email afin d envoyer le email pour la récupèration du mot de passe
  
        // Si l'email est vide alors on ne traite pas
        if(empty($email)){
          $valid = false;
          $er_mail = "Il faut mettre un email";
        }
  
        // On vérifie que l'email est valide, et si il existe
        if(filter_var($email,FILTER_VALIDATE_EMAIL)) {
          $req = $DB->query("SELECT * FROM users WHERE email = ?", array($email));
          $req = $req->fetch();
  
          if(isset($req['email'])){

            // On n'éxecute l'opération que si l'utilisateur n'a pas fait de demande auparavant (ex: 48h avant)
            if($req['reset_pass'] == 0){

              $date_creation = date('Y-m-d H:i:s');
              // Génération d'un token aléatoire pour reset du mdp
              $token = bin2hex(random_bytes(24));

              // Sendinblue
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
              $sendSmtpEmail['params'] = array('NAME'=>$req['username'], 'URL'=>$url . '/recuperation?token=' . $token );
              $sendSmtpEmail['headers'] = array('X-Mailin-custom'=>'MIME-version:1.0|Content-type:text/html; charset=utf-8');
              
              try {
                $result = $apiInstance->sendTransacEmail($sendSmtpEmail);
              } catch (Exception $e) {
                echo 'Exception when calling SMTPApi->sendTransacEmail: ', $e->getMessage(), PHP_EOL;
              }

              $_SESSION['recup_email'] = $email;
  
              $DB->insert("INSERT INTO recuperation(email, token, date_creation) VALUES(?, ?, ?)", array($email, $token, $date_creation));
              $DB->insert("UPDATE users SET reset_pass = 1 WHERE email = ?", array($req['email']));

              header('location: recuperation?section=email');
              exit;
            } else {
              header('location: recuperation?section=email');
              exit;
            }
          }
        }
      }
    }
?>
  <div class="corps">
    <h2>Mot de passe oublié</h2>
    <p>En rentrant votre adresse email, vous débuterez le processus de réinitialisation de votre mot de passe. Nous vous enverrons un email qui vous dirigera vers la page où vous pourrez choisir un nouveau mot de passe.</p>
      <form method="post" class="aligncenter">
      <?php if (isset($er_mail)){ ?>
        <div><?= $er_mail ?></div>
      <?php } ?>
      <input class="entreeCarnet" type="email" placeholder="Adresse email" name="email" value="<?php if(isset($email)){ echo $email; }?>" required>
      <button  class="boutonReset" type="submit" name="oublie">Envoyer</button>
    </form>
  </div>
<?php
  }elseif(isset($_GET['token']) AND !isset($_GET['section'])){
    if(isset($_SESSION['recup_email'])){
      $email = $_SESSION['recup_email'];
      $req = $DB->query("SELECT * FROM recuperation WHERE email = ?", array($email));
      $req = $req->fetch();

      if($_GET['token'] == $req['token']){
        if(strtotime($req['date_creation']) >= strtotime('-1 day')){

          if(!empty($_POST)){
            extract($_POST);
            $valid = true;
        
            if (isset($_POST['nouv'])){
              $mdp = htmlspecialchars($_POST['mdp']);
              $mdp2 = htmlspecialchars($_POST['mdp2']);
              $mdplength = strlen($mdp);
        
              // Si le mot de passe est vide alors on ne traite pas
              if(!empty($_POST['mdp']) AND !empty($_POST['mdp2'])) {
        
                if($mdp == $mdp2) {
                  if($mdplength >= 8) {

                    $req = $DB->query("SELECT * FROM users WHERE email = ?", array($email));
                    $req = $req->fetch();
                    
                    $mdp = crypt($mdp, '$6$rounds=5000$' . $clesecrete);

                    // Sendinblue
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
                    $sendSmtpEmail['templateId'] = 6;
                    $sendSmtpEmail['params'] = array('NAME'=>$req['username'], 'SENDER'=>'admin@ceans.cf');
                    $sendSmtpEmail['headers'] = array('X-Mailin-custom'=>'MIME-version:1.0|Content-type:text/html; charset=utf-8');
                    
                    try {
                      $result = $apiInstance->sendTransacEmail($sendSmtpEmail);
                    } catch (Exception $e) {
                      echo 'Exception when calling SMTPApi->sendTransacEmail: ', $e->getMessage(), PHP_EOL;
                    }
        
                    $DB->insert("UPDATE users SET password = ?, reset_pass = 0 WHERE email = ?", array($mdp, $req['email']));
                    $DB->insert("DELETE FROM recuperation WHERE email = ?", array($req['email']));

                    header('location: recuperation?section=resetmdp');
                    exit;

                  }else{
                    $er_mdp = "Pour des raisons de sécurité, il vaut mieux utiliser un mot de passe ayant une longueur de 8 caractères ou plus";
                  }
                }else{
                  $er_mdp = "Vos mots de passe ne correspondent pas !";
                }
              }else{
                $er_mdp = "Veuillez rentrer un nouveau mot de passe !";
              }
            }
          }
        ?>
          <div class="corps">
            <h2>Choisissez un nouveau mot de passe</h2>
            <form method="post" class="aligncenter">
              <?php if (isset($er_mdp)){ ?>
                <div class="erreur"><?= $er_mdp ?></div>
              <?php } ?>
              <div class="groupeConnexion">
              <label class="logoConnexion"><i class="fas fa-lock"></i>
                <input class="parametreConnexion" type="password" placeholder="Votre mot de passe" id="mdp" name="mdp" required>
              </label>
              </div>
              <input class="parametreConnexion" type="password" placeholder="Confirmez votre mot de passe" id="mdp2" name="mdp2" required>
              <button class="boutonConnexion" type="submit" name="nouv">Envoyer</button>
            </form>
          </div>
        <?php
        }else{
          $er = "Ce lien n'est plus valide";
        }
      }else{
        $er = "Ce lien n'est pas valide";
      }
    }else{
      $er = "Ce lien n'est pas valide";
      if (isset($er)){ ?>
        <div class="corps">
          <div class="erreur"><?= $er ?></div>
        </div>
      <?php }
    }
  }elseif(!isset($_GET['token']) AND isset($_GET['section'])){
    if($_GET['section'] == 'email'){ ?>
      <div class="corps">
        <h2 class="valide">Un email vous a été envoyé</h2>
        <p>Cet email contient un lien qui vous dirigera vers une page où vous pourrez changer de mot de passe. Faites toujours attention à choisir un mot de passe sécurisé.</p>
      </div>
    <?php }elseif($_GET['section'] == 'resetmdp'){ ?>
      <div class="corps">
        <h2 class="valide">Votre mot de passe a été changé</h2>
        <p>Vous devrez désormais vous connecter avec votre nouveau mot de passe. Vous allez désormais être automatiquement redirigé vers la page de connexion...</p>
      </div>
      <?php
      header("refresh:6;url=connexion");
		  exit;
    }
  }
?>
<?php include('parts/footer.php'); //on inclus le footer?>