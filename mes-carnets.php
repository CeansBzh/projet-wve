<?php include('parts/header.php'); ?>
<?php
  require_once('vendor/autoload.php');
  use Cocur\Slugify\Slugify;
  // S'il n'y a pas de session alors on ne va pas sur cette page
  if(!isset($_SESSION['id'])){
    header('Location: connexion');
    exit;
  }

  $req = $DB->query("SELECT c.*, u.username
    FROM carnets c
    LEFT JOIN users u ON u.id = c.id_user
    WHERE u.id = ?
    ORDER BY c.date_creation DESC", array($_SESSION['id']));
  $req = $req->fetchAll();
  
?>
<div class="corps">
	<h1 class="titre">Mes carnets de voyage</h1>

	<?php
		if (isset($_SESSION['id'])){
	?>
		<a href="nouv-carnet" role="button" aria-pressed="true"><button type="button">Créer un carnet</button></a>
  <?php
    }foreach($req as $r){
      $slugify = new Slugify();
	?>
  <a href="carnets/<?= $slugify->slugify($r['titre']) ?>-<?= $r['id'] ?>">
    <div class="listeCarnets">
      <img alt="La bannière du voyage <?= $r['titre'] ?> de <?= $r['username'] ?>." src="images/image.png" class="imageCarnets" />
      <div class="descriptionCarnets">
        <h2><?= $r['titre'] ?></h2>
        <p><?php
          $phpdate = strtotime($r['date_debut']);
          setlocale(LC_TIME, 'fr_FR.utf8', 'fra');
          $month_name = strftime( "%B %Y", $phpdate );
          $month_name= ucfirst($month_name);
          echo($month_name);
        ?> - <?php
        if(strtotime($r['date_fin']) != -3600){
          $date_a = new DateTime($r['date_debut']);
          $date_b = new DateTime($r['date_fin']);
          $interval = date_diff($date_a,$date_b);
          echo $interval->format('%d');
          echo 'J';
        }else{
          echo 'Durée indéterminée ;)';
        }
        ?></p>
      </div>
    </div>
  </a>

	<?php
			}
	?>
</div>
<?php include('parts/footer.php'); ?>
