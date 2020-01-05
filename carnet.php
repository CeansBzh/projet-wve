<?php
include('parts/custom.php');
require_once('vendor/autoload.php');
use Cocur\Slugify\Slugify;
$slugify = new Slugify();

$req = $DB->query("SELECT c.*, u.username FROM carnets c LEFT JOIN users u ON u.id = c.id_user WHERE c.id = ?", array($_GET['id']));
$req = $req->fetch();
$slug = $slugify->slugify($req['titre']);
//if($slug != $_GET['titre']){
//  header('Location:' . $url);
//  exit;
//}

?>
<div class="cadreBanniereCarnets">
  <img alt="La bannière du voyage <?= $req['titre'] ?> de <?= $req['username'] ?>." src="images/image.png" class="banniereCarnets" />
  <div class="detailsBanniereCarnets">
    <h2><?= $req['titre']; ?> - <?php 
      $phpdate = strtotime($req['date_debut']);
      setlocale(LC_TIME, 'fr_FR.utf8', 'fra');
      $month_name = strftime( "%B %Y", $phpdate );
      $month_name= ucfirst($month_name);
      echo($month_name); ?></h2>
    <p>Du <?php
      echo(date_format(date_create($req['date_debut']), 'd'));
      echo(' ');
      echo($month_name);
    ?> au <?php
      echo(date_format(date_create($req['date_fin']), 'd'));
      echo(' ');
      $phpdate = strtotime($req['date_fin']);
      $month_name = strftime( "%B %Y", $phpdate );
      $month_name= ucfirst($month_name);
      echo($month_name);
    ?></p>
  </div>
</div>
<div class="corps">
  <p class="descriptionCarnets"><?= $req['description']; ?></p>
</div>
<?php include('parts/footer.php'); //on inclus le footer?>