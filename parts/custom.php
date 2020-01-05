<?php //on inclus le header à la main car il faut le tag "base"
session_start();
include('connexionDB.php');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="description" content="Voyages est un créateur de carnets de voyages. Utilisez cette application afin d'organiser vos vacances en créant des nouveaux voyages et en ajoutant des étapes fournies en textes et photos. Puis partagez-le !">
<base href="<?= $url ?>" />
<title>Voyages - Carnet de bord</title>
<link rel="icon" type="image/png" href="<?= $url ?>images/logo.png" />
<link rel="stylesheet" href="<?= $url ?>style.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css" crossorigin="anonymous">
</head>
<body>
<noscript>
    <div style="text-align: center; border: 3px solid red; padding: 10px">
      <span style="color:red; font-weight: 700;">Il semblerait que vous bloquiez le Javascript :/</span>
    </div>
  </noscript>
<header>
  <h1 class="logo"><a href="<?= $url ?>">Voyages</a></h1>
</header>
<main>
<nav>
  <ul class="menu">
    <li class="item"><a href="<?= $url ?>">Accueil</a></li>
    <li class="item"><a href="<?= $url ?>actualite">Actualités</a></li>
    <?php
      if (!isset($_SESSION['id'])) {
    ?>
      <li class="item"><a href="<?= $url ?>nouv-carnet">Créer un carnet</a></li>
      <li class="item button"><a href="<?= $url ?>connexion">Connexion</a></li>
      <li class="item button secondary"><a href="<?= $url ?>inscription">Inscription</a></li>
    <?php
      } else {
    ?>
      <li class="item"><a href="<?= $url ?>profil">Profil</a></li>
      <li class="item button"><a href="<?= $url ?>mes-carnets">Mes carnets</a></li>
      <li class="item button secondary"><a href="<?= $url ?>deconnexion">Déconnexion</a></li>
    <?php
      }
    ?>
    <li class="toggle"><a href="<?= $url ?>carnets/<?= $_GET['titre'] ?>-<?= $_GET['id'] ?>#"><i class="fas fa-bars"></i></a></li>
  </ul>
</nav>