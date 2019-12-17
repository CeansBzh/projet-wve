<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<title>MultiActu</title>
		<link rel="stylesheet" href="style.css" />
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	</head>
	<body>
		<header>
			<h1>MultiActu</h1>
			<h2>L'actualité en un clic</h2>
		</header>
		<div class="corps">
			<h2 class="titre_corps">Derniers articles</h2>
			<div id="main"></div>
		</div>
		<script type="text/javascript" language="javascript">
		$(document).ready(function() { /// Wait till page is loaded
		      $('#main').load('feed.php #main', function() {
		           /// can add another function here
		      });
		   }); //// End of Wait till page is loaded
		</script>
	</body>
</html>
