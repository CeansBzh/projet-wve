<?php

if(basename($_SERVER['PHP_SELF']) === "connexion.php"){
    // S'il y a une session alors on ne retourne plus sur cette page
    if (isset($_SESSION['id'])){
    header('Location: index');
    exit;
    }
}

// Si la variable "$_Post" contient des informations alors on les traitres
if(!empty($_POST)){
    extract($_POST);
    $valid = true;

    if (isset($_POST['connexion'])){
        $email = htmlentities(strtolower(trim($email)));
        $mdp = trim($mdp);

        if(empty($email)){ // Vérification qu'il y est bien un email de renseigné
            $valid = false;
            $er_mail = "Sans email, on ne sait pas à qui on a affaire...";
        }
        if(empty($mdp)){ // Vérification qu'il y est bien un mot de passe de renseigné
            $valid = false;
            $er_mdp = "Il manque votre mot de passe mais ne vous inquiétez pas, on ne le dira à personne ;)";
        }
        // On fait une requête pour savoir si le couple email / mot de passe existe bien car le email est unique !
        $req = $DB->query("SELECT * FROM users WHERE email = ? AND password = ?", array($email, crypt($mdp, '$6$rounds=5000$' . $clesecrete)));
        $req = $req->fetch();

          // Si on a pas de résultat alors c'est qu'il n'y a pas d'utilisateur correspondant au couple email / mot de passe
        if ($req['id'] == ""){
            $valid = false;
            $er_mail = "Email ou mot de passe incorrect... On ne sait pas lequel :/";
		}elseif($req['confirmation_token'] == 0){ // Si le compte n'est pas confirmé par mail alors pas de connexion
			$valid = false;
            $er_mail = "Veuillez confirmer votre compte à l'aide du mail qui vous a été envoyé lors de votre inscription";
        }elseif($req['reset_pass'] == 1){ // On remet à zéro la demande de nouveau mot de passe s'il y a bien un couple email / mot de passe
            $DB->insert("UPDATE users SET reset_pass = 0 WHERE id = ?", array($req['id']));
        }
        // S'il y a un résultat alors on va charger la SESSION de l'utilisateur en utilisateur les variables $_SESSION
        if ($valid){
            $_SESSION['id'] = $req['id']; // id de l'utilisateur unique pour les requêtes futures
            $_SESSION['username'] = $req['username'];
            $_SESSION['email'] = $req['email'];
			$_SESSION['role'] = $req['role'];
            
            if(basename($_SERVER['PHP_SELF']) === "connexion.php"){
                header('Location:  index');
                exit;
            }else{
                header('Location: '.$_SERVER['REQUEST_URI']);
                exit;
            }
        }
    }
}
?>