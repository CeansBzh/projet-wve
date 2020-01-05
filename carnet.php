<?php
include('parts/custom.php');
require_once('vendor/autoload.php');
use Cocur\Slugify\Slugify;
$slugify = new Slugify();

$req = $DB->query("SELECT * FROM carnets WHERE id = ?", array($_GET['id']));
$req = $req->fetch();
$slug = $slugify->slugify($req['titre']);
if($slug != $_GET['titre']){
  header('Location:' . $url);
  exit;
}

?>
<div class="corps">
  <h2><?= $req['titre']; ?></h2>
  <p><?= $req['description']; ?></p>
  <p><?= $_GET['id']; ?></p>
</div>
<?php include('parts/footer.php'); //on inclus le footer?>