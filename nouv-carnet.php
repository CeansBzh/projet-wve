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
      $date_debut = (int) htmlentities(trim($date_debut));
      $date_fin = (int) htmlentities(trim($date_fin));

      if(empty($titre)){
        $valid = false;
        $er_titre = ("Veuillez renseigner le titre de votre carnet de voyage");
      }

      if(empty($contenu)){
        $valid = false;
        $er_contenu = ("Veuillez renseigner la description de votre carnet de voyage");
      }

      if(empty($categorie)){
        $valid = false;
        $er_categorie = "Veuillez une catégorie";
      }else{

        // On vérifit que la catégorie existe
        $verif_cat = $DB->query("SELECT id, titre FROM categorie WHERE id = ?", array($categorie));
				$verif_cat = $verif_cat->fetch();

        if (!isset($verif_cat['id'])){
          $valid = false;
          $er_categorie = "Cette catégorie n'existe pas";
        }
      }

      if($valid){
        $date_creation = date('Y-m-d H:i:s');
        $DB->insert("INSERT INTO blog (id_user, titre, text, date_creation, id_categorie) VALUES (?, ?, ?, ?, ?)", array($_SESSION['id'], $titre, $contenu, $date_creation, $categorie));

        header('Location: carnets');
        exit;
      }
    }
  }
?>
<div class="corps">
  <h1>Créer un (nouveau) carnet de voyage</h1>

    <form method="post">
      <section>
          <h2>Détails du voyage</h2>
          <p>
            <?php if (isset($er_titre)){?>
              <div class="erreur"><?= $er_titre ?></div>
            <?php } ?>
            <label for="titre">Titre:</label>
            <input type="text" id="titre" placeholder="Voyage en Patagonie" name="titre" value="<?php if(isset($titre)){ echo $titre; }?>" maxlength="50">
          </p>
          <p>
            <?php if (isset($er_description)){?>
              <div class="erreur"><?= $er_description ?></div>
            <?php } ?>
            <label for="description">Résumé:</label>
            <textarea id="description" rows="3" placeholder="Un voyage incroyable entre amis, à l'autre bout du monde !" name="description" maxlength="255"><?php if(isset($description)){ echo $description; }?></textarea>
          </p>
      </section>
      <section>
          <h2>Quand ?</h2>
          <?php if (isset($er_date)){?>
            <div class="erreur"><?= $er_date ?></div>
          <?php } ?>
          <p>
            <label for="date_debut">Date de début:</label>
            <input type="date" id="date_debut" name="date_debut" min="<?php echo $date_ajd ?>" value="<?php echo $date_voyage ?>">
          </p>
          <p>
            <label for="date_fin">Date de fin:</label>
            <input type="date" id="date_fin" name="date_fin">
          </p>
      </section>
      <section>
          <button type="submit" name="creer-carnet">Ajouter mon voyage</button>
      </section>
    </form>
  </div>
<?php include('parts/footer.php'); ?>
