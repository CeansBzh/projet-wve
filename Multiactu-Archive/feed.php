<?php
require_once('php/autoloader.php');

// We'll process this feed with all of the default options.
$feed = new SimplePie();

// Set the feed to process.
$feed->set_feed_url('https://www.unrealengine.com/en-US/rss');

//Range les articles dans l'ordre chronologique
$feed->enable_order_by_date(true);

// Run SimplePie.
$feed->init();

// This makes sure that the content is sent to the browser as text/html and the UTF-8 character set (since we didn't change it).
$feed->handle_content_type();
?>
<!DOCTYPE html>
<html>
  <head>
  <link rel="stylesheet" href="style.css" />
  </head>
  <div id="main">
    <?php
    //On recommence le code suivant pour chaque article
    foreach ($feed->get_items() as $item):
    ?>
    <?php
    $premiereImage = function ($html) {
      if (preg_match('/<img.+?src="(.+?)"/', $html, $matches))
      {
        return $matches[1];
      }
      else return '';
    };
    //$media_group = $item->get_item_tags('', 'enclosure');
    //$file = $media_group[0]['attribs']['']['url'];
    //echo $file;
    ?>

      <div class="item">
        <img class="image-article" src="<?php echo $premiereImage($item->get_content()); ?>"/>
        <h2 class="titre-article"><a href="<?php echo $item->get_permalink(); ?>"><?php echo $item->get_title(); ?></a></h2>
        <p class="desc-article"><?php echo $item->get_description(); ?><br/></p>
        <p class="date-article"><small>Publié le <?php echo $item->get_date('j F Y | g:i a'); ?></small></p>
      </div>

    <?php endforeach; ?>
  </div>
</html>
