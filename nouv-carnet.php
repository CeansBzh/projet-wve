<?php include('parts/header.php'); ?>
<?php

  $date_ajd = date('Y-m-d');

  $date = date_create($date_ajd);
  date_add($date, date_interval_create_from_date_string('1 months'));
  $date_voyage = date_format($date, 'Y-m-d');

  if(!empty($_POST)){
    extract($_POST);
    $valid = true;

    if (isset($_POST['creer-carnet'])){
      $titre  = (string) htmlentities(trim($titre));
			$description = (string) htmlentities(trim($description));
      $date_debut = date('Y-m-d', strtotime($_POST['date_debut']));
      $date_fin = date('Y-m-d', strtotime($_POST['date_fin']));

      if(empty($titre)){
        $valid = false;
        $er_titre = ("Veuillez renseigner le titre de votre carnet de voyage");
      }

      if(empty($description)){
        $valid = false;
        $er_description = ("Veuillez renseigner la description de votre carnet de voyage");
      }

      if(strtotime($date_debut) > strtotime($date_fin)){
        $valid = false;
        $er_date = ("Comptez vous revenir de voyage avant d'être parti ? ;)");
      }

      if($valid){
			  if (isset($_SESSION['id'])){
          $date_creation = date('Y-m-d H:i:s');
          $DB->insert("INSERT INTO carnets (id_user, date_creation, titre, date_debut, date_fin, description) VALUES (?, ?, ?, ?, ?, ?)", array($_SESSION['id'], $date_creation, $titre, $date_debut, $date_fin, $description));
          header('Location: carnets');
          exit;
        }
      }
    }
  }
?>
<div class="corps">
  <h1>Créer un (nouveau) carnet de voyage</h1>

    <form class="carnet" method="post">
      <section>
          <h2>Détails</h2>
          <p>
            <?php if (isset($er_titre)){?>
              <div class="erreur"><?= $er_titre ?></div>
            <?php } ?>
            <label for="titre">Titre:</label>
            <input class="entreeCarnet" type="text" id="titre" placeholder="Voyage en Patagonie" name="titre" value="<?php if(isset($titre)){ echo $titre; }?>" maxlength="50">
          </p>
          <p>
            <?php if (isset($er_description)){?>
              <div class="erreur"><?= $er_description ?></div>
            <?php } ?>
            <label for="description">Résumé:</label>
            <textarea class="entreeCarnet" id="description" rows="3" placeholder="Un voyage incroyable entre amis, à l'autre bout du monde ! (255 caractères maximum)" name="description" maxlength="255"><?php if(isset($description)){ echo $description; }?></textarea>
          </p>
      </section>
      <section>
          <h2>Quand ?</h2>
          <?php if (isset($er_date)){?>
            <div class="erreur"><?= $er_date ?></div>
          <?php } ?>
          <p>
            <label for="date_debut">Date de début:</label>
            <input class="entreeCarnet" type="date" id="date_debut" name="date_debut" min="<?php echo $date_ajd ?>" value="<?php if(isset($date_debut)){ echo $date_debut; }else{ echo $date_voyage; }?>">
          </p>
          <p>
            <label for="date_fin">Date de fin:</label>
            <input class="entreeCarnet" type="date" id="date_fin" name="date_fin">
          </p>
      </section>
      <?php if (isset($_SESSION['id'])){ ?>
        <button class="entreeCarnet boutonAction" type="submit" name="creer-carnet">Ajouter mon voyage</button>
      <?php }else{ ?>
        <button class="entreeCarnet boutonAction popupModalOuvre" type='button'>Me connecter et ajouter mon voyage</button>
      <?php } ?>
    </form>
    <div class="modal">
        <div class="modal-content">
            <span class="popupModalFerme"><i class="fas fa-times"></i></span>
            <h1>Le mec qui lis ça est super con</h1>
        </div>
    </div>
  </div>
<?php include('parts/footer.php'); ?>