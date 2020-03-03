<?php
include('parts/custom.php');
require_once('vendor/autoload.php');
use Cocur\Slugify\Slugify;
$slugify = new Slugify();

$req = $DB->query("SELECT c.*, u.username FROM carnets c LEFT JOIN users u ON u.id = c.id_user WHERE c.id = ?", array($_GET['id']));
$req = $req->fetch();
$slug = $slugify->slugify($req['titre']);

if(isset($_GET['edition'])){
  if(isset($_SESSION['id'])){
    if($req['id_user'] != $_SESSION['id']){
      header('Location:' . $url);
      exit;
    }
  }else{
    header('Location:' . $url);
    exit;
  }
}

?>
<div class="cadreBanniereCarnets">
  <div id="banniere"></div>
  <img alt="La bannière du voyage <?= $req['titre'] ?> de <?= $req['username'] ?>." src="images/image.png" class="banniereCarnets"/>
  <?php
  if (isset($_SESSION['id'])){
    if ($req['id_user'] == $_SESSION['id']){
      if (!isset($_GET['edition'])){
  ?>
      <a href="<?=strtok($_SERVER["REQUEST_URI"], '?'); ?>?edition" role="button" aria-pressed="true" class="redirEditionCarnet"><button type="button">Éditer votre carnet</button></a>
  <?php
      }else{ ?>
      <a href="<?=strtok($_SERVER["REQUEST_URI"], '?'); ?>" role="button" aria-pressed="true" class="redirEditionCarnet"><button type="button">Sortir de l'édition</button></a>
      <input type="file" id="image" class="rogneEditionCarnet">
  <?php
      }
    }
  }
  ?>
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

<script type="text/javascript">

var resize = $('#banniere').croppie({
  enableExif: true,
  enableOrientation: true,    
  viewport: { // Default { width: 100, height: 100, type: 'square' } 
      width: 1700,
      height: 400,
      type: 'square' //square
  }
});

$('#image').on('change', function () { 
  var reader = new FileReader();
  reader.onload = function (e) {
    resize.croppie('bind',{
      url: e.target.result
    }).then(function(){
      console.log('jQuery bind complete');
    });
  }
  reader.readAsDataURL(this.files[0]);
});

$('.btn-upload-image').on('click', function (ev) {
  resize.croppie('result', {
    type: 'canvas',
    size: 'viewport'
  }).then(function (img) {
    $.ajax({
      url: "croppie.php",
      type: "POST",
      data: {"image":img},
      success: function (data) {
        html = '<img src="' + img + '" />';
        $("#preview-crop-image").html(html);
      }
    });
  });
});
</script>
<?php include('parts/footer.php'); //on inclus le footer?>