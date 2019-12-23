<?php
	session_start();
	include('connexionDB.php');
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>Voyages - Carnet de bord</title>
	<link rel="stylesheet" href="style.css" />
	<script type="text/javascript" src="js/main.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>
<body>
	<header>
		<h1 class="logo"><a href="index">Voyages</a></h1>
	</header>
	<main>
			<?php include('parts/nav.php'); ?>
