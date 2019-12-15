<?php
require_once('php/autoloader.php');

// Create a new class that extends an existing class
class SimplePie_Extras extends SimplePie {

}

// We'll process this feed with all of the default options.
$feed = new SimplePie_Extras();

// Set the feed to process.
$feed->set_feed_url('https://www.unrealengine.com/en-US/rss');

//Range les articles dans l'ordre chronologique
$feed->enable_order_by_date(true);

// Run SimplePie.
$feed->init();

// This makes sure that the content is sent to the browser as text/html and the UTF-8 character set (since we didn't change it).
$feed->handle_content_type();

// Let's begin our XHTML webpage code.  The DOCTYPE is supposed to be the very first thing, so we'll keep it on the same line as the closing-PHP tag.
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<title>WVE Actualité</title>
		<link rel="stylesheet" href="style.css" />
		<script type="text/javascript" src="script.js"></script>
	</head>
	<body>
		<header>
			<h1>PolyActu</h1>
			<h2>L'actualité en un clic</h2>
		</header>
		<div class="corps">
			<h2 class="titre_corps">Derniers articles</h2>
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
	  		else return 'error.png';
	  	};
			?>

				<div class="item">
					<h2 class"titre-article"><a href="<?php echo $item->get_permalink(); ?>"><?php echo $item->get_title(); ?></a></h2>
					<p><?php echo $item->get_description(); ?></p>
					<p><small>Posted on <?php echo $item->get_date('j F Y | g:i a'); ?></small></p>
					<img src="<?php echo $premiereImage($item->get_content()); ?>"/>
				</div>

			<?php endforeach; ?>
		</div>
	</body>
</html>
