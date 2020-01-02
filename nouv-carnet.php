<?php include('parts/header.php'); ?>
<?php include('php/connexionData.php'); //on inclus le code de connexion?>
<?php

	$date_ajd = date('Y-m-d');

	$date = date_create($date_ajd);
	date_add($date, date_interval_create_from_date_string('1 months'));
	$date_voyage = date_format($date, 'Y-m-d');

	if(!empty($_POST)){
		extract($_POST);
		$valid = true;

		if (isset($_POST['creer-carnet'])){
			$titre  = (string) htmlentities(trim($titre));
			$description = (string) htmlentities(trim($description));
			$date_debut = date('Y-m-d', strtotime($_POST['date_debut']));
			$date_fin = date('Y-m-d', strtotime($_POST['date_fin']));

			if(empty($titre)){
				$valid = false;
				$er_titre = ("Veuillez renseigner le titre de votre carnet de voyage");
			}

			if(empty($description)){
				$valid = false;
				$er_description = ("Veuillez renseigner la description de votre carnet de voyage");
			}

			if(strtotime($date_fin) != -3600){
				if(strtotime($date_debut) > strtotime($date_fin)){
					$valid = false;
					$er_date = ("Comptez vous revenir de voyage avant d'être parti ? ;)");
				}
			}

			if($valid){
				if (isset($_SESSION['id'])){
					$date_creation = date('Y-m-d H:i:s');
					$DB->insert("INSERT INTO carnets (id_user, date_creation, titre, date_debut, date_fin, description) VALUES (?, ?, ?, ?, ?, ?)", array($_SESSION['id'], $date_creation, $titre, $date_debut, $date_fin, $description));
					header('Location: mes-carnets');
					exit;
				}
			}
		}
	}
?>
<div class="corps">
	<h1>Créer un (nouveau) carnet de voyage</h1>
		<form method="post">
			<section>
					<h2>Détails</h2>
					<p>
						<?php if (isset($er_titre)){?>
							<div class="erreur"><?= $er_titre ?></div>
						<?php } ?>
						<label for="titre">Titre:</label>
						<input class="entreeCarnet" type="text" id="titre" placeholder="Voyage en Patagonie" name="titre" value="<?php if(isset($titre)){ echo $titre; }?>" maxlength="50">
					</p>
					<p>
						<?php if (isset($er_description)){?>
							<div class="erreur"><?= $er_description ?></div>
						<?php } ?>
						<label for="description">Résumé:</label>
						<textarea class="entreeCarnet" id="description" rows="3" placeholder="Un voyage incroyable entre amis, à l'autre bout du monde ! (255 caractères maximum)" name="description" maxlength="255"><?php if(isset($description)){ echo $description; }?></textarea>
					</p>
			</section>
			<section>
					<h2>Quand ?</h2>
					<?php if (isset($er_date)){?>
						<div class="erreur"><?= $er_date ?></div>
					<?php } ?>
					<p>
						<label for="date_debut">Date de début:</label>
						<input class="entreeCarnet" type="date" id="date_debut" name="date_debut" min="<?php echo $date_ajd ?>" value="<?php if(isset($date_debut)){ echo $date_debut; }else{ echo $date_voyage; }?>">
					</p>
					<p>
						<label for="date_fin">Date de fin:</label>
						<input class="entreeCarnet" type="date" id="date_fin" name="date_fin" min="<?php echo $date_ajd ?>" value="<?php if(isset($date_fin)){ echo $date_fin; }else{ echo ''; }?>">
						<button id="reset" class="boutonReset" type='button'>Je ne sais pas</button>
					</p>
			</section>
			<?php if (isset($_SESSION['id'])){ ?>
				<button class="entreeCarnet boutonAction" type="submit" name="creer-carnet">Ajouter mon voyage</button>
			<?php }else{ ?>
				<button class="entreeCarnet boutonAction popupModalOuvre" type='button'>Me connecter et ajouter mon voyage</button>
			<?php } ?>
		</form>
		<div class="modal">
				<div class="modal-content">
						<span class="popupModalFerme"><i class="fas fa-times"></i></span>
						<form method="post" class="connexion connexionModale">
							<div class="teteConnexion">
								<h3>Connexion</h3>
								<p>Accédez à votre espace personnel et profitez de toutes les fonctionnalités</p>
							</div>
							<div class="groupeConnexion">
								<?php
								if (isset($er_mail)){
									echo "<div class=\"erreur\">". $er_mail . "</div>";
								}
								?>
								<label class="logoConnexion"><i class="fas fa-envelope"></i>
								<input class="parametreConnexion" type="email" placeholder="adresse@exemple.fr" name="email" value="<?php if(isset($email)){ echo $email; }?>" required>
								</label>
							</div>
							<div class="groupeConnexion">
								<?php
								if (isset($er_mdp)){
									echo "<div class=\"erreur\">". $er_mdp . "</div>";
								}
								?>
								<label class="logoConnexion"><i class="fas fa-lock"></i>
								<input class="parametreConnexion" type="password" placeholder="Mot de passe" name="mdp" value="<?php if(isset($mdp)){ echo $mdp; }?>" required>
								</label>
							</div>
							<button class="boutonConnexion" type="submit" name="connexion">Se connecter</button>
							<div class="piedConnexion">
								<a href="reinitialisation">Mot de passe oublié ?</a>
							</div>
						</form>
				</div>
		</div>
	</div>
<script type="text/javascript">
	document.getElementById('reset').onclick= function() {
		var field= document.getElementById('date_fin');
		field.value= field.defaultValue;
	};

	// Run on page load
	window.onload = function() {
		
		// If sessionStorage is storing default values (ex. name), exit the function and do not restore data
		if (sessionStorage.getItem('titre') == null) {
			return;
		}

		if(document.referrer == "<?php echo $url ?>/nouv-carnet"){
				// If values are not blank, restore them to the fields
				var titre = sessionStorage.getItem('titre');
				if (titre !== null) $('#titre').val(titre);
				
				var description = sessionStorage.getItem('description');
				if (description !== null) $('#description').val(description);

				var date_debut= sessionStorage.getItem('date_debut');
				if (date_debut!== null) $('#date_debut').val(date_debut);

				var date_fin= sessionStorage.getItem('date_fin');
				if (date_fin!== null) $('#date_fin').val(date_fin);
		}
  }
  
  // Popup modale
  var modal = document.querySelector(".modal");
	var trigger = document.querySelector(".popupModalOuvre");
	var closeButton = document.querySelector(".popupModalFerme");

	function toggleModal() {
		modal.classList.toggle("show-modal");
	}

	function windowOnClick(event) {
		if (event.target === modal) {
			toggleModal();
		}
	}

	trigger.addEventListener("click", toggleModal);
	closeButton.addEventListener("click", toggleModal);
	window.addEventListener("click", windowOnClick);


	// Before refreshing the page, save the form data to sessionStorage
	window.onbeforeunload = function() {
			sessionStorage.setItem("titre", $('#titre').val());
			sessionStorage.setItem("description", $('#description').val());
			sessionStorage.setItem("date_debut", $('#date_debut').val());
			sessionStorage.setItem("date_fin", $('#date_fin').val());
	}
</script>
<?php include('parts/footer.php'); ?>