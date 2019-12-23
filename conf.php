<?php include('parts/header.php'); //on inclus le header?>
<?php

  if (isset($_SESSION['id'])){
    header('Location: index');
    exit;
  }

  $id = (int) $_GET['id'];
  $token = (String) htmlentities($_GET['token']);
  $valid = true;
  $err_mess = '';

  if (!isset($id)){
    $valid = false;
    $err_mess = "Le lien est erroné";
  }elseif(!isset($token)){
    $valid = false;
    $err_mess = "Le lien est erroné";
  }

  if($valid){
    $req = $DB->query("SELECT id FROM users WHERE id = ? AND token = ?", array($id, $token));
    $req = $req->fetch();

    if(!isset($req['id'])){
      $valid = false;
      $err_mess = "Le lien n'est plus valide";
    }else{
      $DB->insert("UPDATE users SET token = NULL, confirmation_token = ? WHERE id = ?", array(date('Y-m-d H:i:s'), $req['id']));
      $info_mess = 'Votre compte a bien été validé</br>';
    }
  }

  if(isset($err_mess)){
    echo $err_mess;
  }

  if(isset($info_mess)){
    echo $info_mess;
		echo 'Redirection automatique vers la page d\'acceuil dans 3 secondes...';
		header("refresh:3;url=index");
		exit;
  }
?>
<?php include('parts/footer.php'); //on inclus le footer?>
