<?php

// Make sure SimplePie is included. You may need to change this to match the location of autoloader.php
// For 1.0-1.2:
#require_once('../simplepie.inc');
// For 1.3+:
require_once('php/autoloader.php');

// We'll process this feed with all of the default options.
$feed = new SimplePie();

// Set the feed to process.
$feed->set_feed_url('https://www.unrealengine.com/en-US/rss');

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
	</head>
	<body>
		<header>
			<h1>PolyActu</h1>
			<h2>L'actualité en un clic</h2>
		</header>
		<div class="corps">
			<h2 class="titre_corps">Derniers articles</h2>
			<?php
			/*
			Here, we'll loop through all of the items in the feed, and $item represents the current item in the loop.
			*/
			foreach ($feed->get_items() as $item):
			?>

				<div class="item">
					<h2><a href="<?php echo $item->get_permalink(); ?>"><?php echo $item->get_title(); ?></a></h2>
					<p><?php echo $item->get_description(); ?></p>
					<p><small>Posted on <?php echo $item->get_date('j F Y | g:i a'); ?></small></p>
				</div>

			<?php endforeach; ?>
		</div>
	</body>
</html>
