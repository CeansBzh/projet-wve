<?php include('parts/header.php'); ?>
<?php
  if (!isset($_SESSION['id'])){
    header('Location: actualite');
    exit;
  }

  if($_SESSION['role'] != '1'){
    header('Location: actualite');
    exit;
  }

  if(!empty($_POST)){
    extract($_POST);
    $valid = true;

    if (isset($_POST['creer-article'])){
      $titre  = (string) htmlentities(trim($titre));
			$contenu = (string) htmlentities(trim($contenu));
      $categorie = (int) htmlentities(trim($categorie));

      if(empty($titre)){
        $valid = false;
        $er_titre = ("Il faut mettre un titre");
      }

      if(empty($contenu)){
        $valid = false;
        $er_contenu = ("Il faut mettre un contenu");
      }

      if(empty($categorie)){
        $valid = false;
        $er_categorie = "Le thème ne peut pas être vide";
      }else{
        // On vérifit que la catégorie existe
        $verif_cat = $DB->query("SELECT id, titre FROM categorie WHERE id = ?", array($categorie));
				$verif_cat = $verif_cat->fetch();

        if (!isset($verif_cat['id'])){
          $valid = false;
          $er_categorie = "Ce thème n'existe pas";
        }
      }

      if($valid){
        $date_creation = date('Y-m-d H:i:s');
        $DB->insert("INSERT INTO blog (id_user, titre, text, date_creation, id_categorie) VALUES (?, ?, ?, ?, ?)", array($_SESSION['id'], $titre, $contenu, $date_creation, $categorie));

        header('Location: actualite');
        exit;
      }
    }
  }
?>
  <h1>Créer mon article</h1>
    <form method="post">
      <?php
      	// S'il y a une erreur sur le nom alors on affiche
        if (isset($er_categorie)){
      ?>
        <div class="er-msg"><?= $er_categorie ?></div>
      <?php
        }
    	?>
              <div class="form-group">
                <div class="input-group mb-3">
                  <select name="categorie" class="custom-select" id="inputGroupSelect01">
                    <?php
                      if(empty($categorie)){
                      ?>
                        <option selected>Sélectionner votre thème</option>
                      <?php
                      }else{
                      ?>
                        <option value="<?= $categorie ?>"><?= $verif_cat['titre'] ?></option>
                      <?php
                      }

                      $req_cat = $DB->query("SELECT * FROM categorie");
                      $req_cat = $req_cat->fetchALL();

                      foreach($req_cat as $rc){
                      ?>
                        <option value="<?= $rc['id'] ?>"><?= $rc['titre'] ?></option>
                      <?php
                      }
                    ?>
                  </select>
                </div>
              </div>
              <?php
                if (isset($er_titre)){
                ?>
                  <div class="er-msg"><?= $er_titre ?></div>
                <?php
                }
              ?>
              <div class="form-group">
                 <input class="form-control" type="text" placeholder="Votre titre" name="titre" value="<?php if(isset($titre)){ echo $titre; }?>">
              </div>
              <?php
                if (isset($er_contenu)){
                  ?>
                    <div class="er-msg"><?= $er_contenu ?></div>
                  <?php
                  }
              ?>
              <div class="form-group">
                <textarea class="form-control" rows="3" placeholder="Écrivez votre article" name="contenu"><?php if(isset($contenu)){ echo $contenu; }?></textarea>
              </div>
              <div class="form-group">
                <button class="btn btn-primary" type="submit" name="creer-article">Envoyer</button>
              </div>
            </form>
<?php include('parts/footer.php'); ?>
