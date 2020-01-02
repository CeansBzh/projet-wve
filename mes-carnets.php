<?php include('parts/header.php'); ?>
<?php

	$req = $DB->query("SELECT b.*, u.username, c.titre AS titre_cat FROM blog b
		LEFT JOIN users u ON u.id = b.id_user
		LEFT JOIN categorie c ON c.id_categorie = b.id_categorie
		ORDER BY b.date_creation DESC");

	$req = $req->fetchAll();
?>
<div class="corps">
	<h1 class="titre">Mes carnets de voyage</h1>

	<?php
			if (isset($_SESSION['id'])){
			if($_SESSION['role'] == '1'){
		?>
		<a href="nouv-article" role="button" aria-pressed="true"><button type="button">Écrire un article</button></a>
		<?php
				}
			}
		?>
	<?php
		foreach($req as $r){
	?>
			<!-- Article -->
			<article itemprop="blogPost" itemscope itemtype="https://schema.org/BlogPosting">
				<div class="headerArticle">
					<h1 class="titreArticle" itemprop="name headline"><?= $r['titre'] ?></h1>
					<p class="dateArticle"><time datetime="<?= $r['date_creation'] ?>" itemprop="datePublished"><?= date_format(date_create($r['date_creation']), 'd/m/Y à H:i'); ?></time></p>
				</div>
					<div class="corpsArticle" itemprop="articleBody">
							<?= nl2br($r['text']); ?>
					</div>
					<footer>
							<div class="auteurArticle" itemprop="author" itemscope itemtype="https://schema.org/Person">
											<span itemprop="name"><?= $r['username'] ?></span>
							</div>
							<span itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
									<meta itemprop="name" content="voyages">
									<span itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">
											<meta itemprop="url" content="images/logo.png">
									</span>
							</span>
							<span itemprop="keywords">
								<p><?= $r['titre_cat'] ?></p>
							</span>
					</footer>
			</article>
	<?php
			}
	?>
</div>
<?php include('parts/footer.php'); ?>
