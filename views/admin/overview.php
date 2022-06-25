<?php
	include_once $_SERVER['DOCUMENT_ROOT'] . "/shop/includes.php";

	Utils::authorize([1], "index.php");
?>

<!doctype HTML>
<html lang="nl">
<head>
	<title>Admin - Overview</title>
	<?php include("../partials/_stylesheets.php"); ?>
</head>
<body>
	<?php include("../partials/_nav.php"); ?>

	<?php include("../partials/_notifications.php"); ?>

	<div id="content-cont" class="scroll-clean">
		<div id="main-wrapper" class="wrapper-nopadding">
		<div class="wrapper-div">

			<div class="wrapper-titel-2">Beheerdersoverzicht</div>

			<div class="wrapper-div-box admin-cont">
				<div class="wrapper-div-box-titel">Producten</div>
				<a href="<?php echo URL ?>views/admin/overview.php?product-add" class="wrapper-div-box-a">Product toevoegen</a>
				<a href="<?php echo URL ?>views/admin/overview.php?product-select" class="wrapper-div-box-a">Product bewerken of verwijderen</a>
			</div>

			<div class="wrapper-div-box admin-cont">
				<div class="wrapper-div-box-titel">Categorieën</div>
				<?php 
				$cats = CategorieModel::getAllcategorien();
				foreach($cats as $cat){
					echo '<div class="wrapper-div-box-a admin-categorie">' . $cat->naam;
					echo '<a href="overview.php?admin-edit-categorie=' . $cat->id . '" title="Bewerken" class="admin-categorie-img icon icon-edit admin-categorie-edit"></a>';
					echo '<a href="' . URL . 'controllers/RequestController.php?admin-delete-categorie=' . $cat->id . '" title="Verwijderen" class="admin-categorie-img icon icon-delete admin-categorie-delete"></a>';
					echo '</div>';
				}

				?>
				

				<?php 
					if(isset($_GET["admin-edit-categorie"])){
						echo '<hr-small></hr-small>';
						$cat = CategorieModel::getCategorie($_GET["admin-edit-categorie"]);
						echo '<form action="../../controllers/RequestController.php" method="POST">';
						echo '<div class="wrapper-div-box-input-label">Bewerk \'' . $cat->naam . '\'</div>';
						echo '<input type="hidden" name="admin-edit-categorie" value="' . $cat->id . '">';
						echo '<input required type="text" name="admin-edit-categorie-naam" class="wrapper-div-box-input" value="' . $cat->naam . '">';
						echo '<input type="submit" class="button-blue button-nomargin">';
						echo '</form>';
					}
				?>

				

				<?php
					if(isset($_GET["admin-add-categorie"])){
						echo '<hr-small></hr-small>';
						echo '<form action="../../controllers/RequestController.php" method="POST">';
						echo '<div class="wrapper-div-box-input-label">Voeg een nieuwe categorie toe</div>';
						echo '<input required type="text" name="admin-add-categorie" class="wrapper-div-box-input">';
						echo '<input type="submit" class="button-blue button-nomargin">';
						echo '</form>';
					}
				?>
				<a href="overview.php?admin-add-categorie" class="icon icon-add admin-categorie-add" title="Voeg een categorie toe"></a>
			</div>

			<div class="wrapper-div-box admin-cont">
				<div class="wrapper-div-box-titel">Gebruikers</div>
				<a href="<?php echo URL ?>views/admin/overview.php?user-add" class="wrapper-div-box-a">Gebruiker toevoegen</a>
				<a href="<?php echo URL ?>views/admin/overview.php?user-select" class="wrapper-div-box-a">Gebruiker verwijderen of bewerken</a>
			</div>

			<div class="wrapper-div-box admin-cont">
				<div class="wrapper-div-box-titel">Algemeen</div>
				<a href="<?php echo URL ?>views/admin/overview.php?bestellingen-view" class="wrapper-div-box-a">Bekijk alle bestellingen</a>
			</div>

			<div class="wrapper-div-box admin-cont">
				<form action="../../controllers/RequestController.php" method="POST">
					<div class="wrapper-div-box-titel">Configuratie</div>
					<div class="wrapper-div-box-input-label">Naam van je winkel</div>
					<div class="clearfix"></div>
					<input type="text" name="algemeen-winkel_naam" class="wrapper-div-box-input" value="<?php echo $_SESSION["configuratie"]->winkel_naam ?>">

					<div class="wrapper-div-box-input-label">Aantal uitgelichte producten</div>
					<div class="clearfix"></div>
					<input type="number" name="algemeen-aantal_uitgelicht" class="wrapper-div-box-input-number" value="<?php echo $_SESSION["configuratie"]->aantal_uitgelicht ?>">

					<div class="clearfix"></div>

					<div class="wrapper-div-box-input-label">Aantal nieuwste producten</div>
					<div class="clearfix"></div>
					<input type="number" name="algemeen-aantal_nieuwste" class="wrapper-div-box-input-number" value="<?php echo $_SESSION["configuratie"]->aantal_nieuwste ?>">

					<input type="hidden" name="admin-edit-config" value="true">
					<input type="submit" class="button-blue button-nomargin" value="Verzenden">
				</form>
			</div>

			<div class="clearfix"></div>

		</div>
			
		</div>
	</div>

	<?php 
		if(isset($_GET["product-add"])){
			?>
			<div class="popup-maincont">
				<div class="popup-cont">
					<div class="popup-titel">
						Voeg een product toe
						<?php
							echo '<a href="' . URL . 'views/admin/overview.php" class="popup-close close" title="Sluiten"></a>';
						?>
						
					</div>
					<form class="popup-content scroll-clean" action="../../controllers/RequestController.php" method="POST" enctype='multipart/form-data'>

						<div class="wrapper-div-input-cont">
							<p class="wrapper-div-input-label">Foto</p>
							<input required type="file" class="wrapper-div-input-input" name="add-product-foto">

							<div class="clearfix"></div>
						</div>

						<div class="wrapper-div-input-cont">
							<p class="wrapper-div-input-label">Naam</p>
							<input required type="text" class="wrapper-div-input-input wrapper-div-input-text" name="add-product-naam">

							<div class="clearfix"></div>
						</div>

						<div class="wrapper-div-input-cont">
							<p class="wrapper-div-input-label">Prijs</p>
							<input required type="number" step='0.01' class="wrapper-div-input-input" name="add-product-prijs">

							<div class="clearfix"></div>
						</div>

						<div class="wrapper-div-input-cont">
							<p class="wrapper-div-input-label">Categorie</p>
							<select required type="number" class="wrapper-div-input-input" name="add-product-categorie">
								<?php
									$cats = CategorieModel::getAllCategorien();
									foreach($cats as $categorie){
										echo '<option value="' . $categorie->id . '">' . $categorie->naam . '</option>';
									}
								?>
							</select>

							<div class="clearfix"></div>
						</div>

						<div class="wrapper-div-input-cont">
							<p class="wrapper-div-input-label">Uitgelicht</p>
							<select required type="number" class="wrapper-div-input-input" name="add-product-uitgelicht">
								<option value="true">Ja</option>
								<option value="false">Nee</option>
							</select>

							<div class="clearfix"></div>
						</div>

						<div class="wrapper-div-input-cont">
							<p class="wrapper-div-input-label">Beschrijving</p>
							<textarea required class="wrapper-div-input-input scroll-clean wrapper-div-input-textarea" name="add-product-beschrijving"></textarea>

							<div class="clearfix"></div>
						</div>

						<div style="clear:both;"></div>

						<input type="hidden" name="admin-add-product" value="true">
						<input class='button-blue' type='submit'>
					</form>
				</div>
			</div>
			<?php
		}

		if(isset($_GET["product-select"])){
			?>
			<div class="popup-maincont">
				<div class="popup-cont popup-wide">
					<div class="popup-titel">
						Alle producten
						<?php
							echo '<a href="' . URL . 'views/admin/overview.php" class="popup-close close" title="Sluiten"></a>';
						?>
					</div>
					<div class="popup-content scroll-clean">
						<table class="bestellingen-table">
							<tr class="bestellingen-tr">
								<th>ID</th>
								<th>Naam</th>
								<th>Prijs</th>
								<th>Categorie</th>
								<th>Uitgelicht</th>
								<th>Toegevoegd</th>
								<th>Actief</th>
								<th></th>
							</tr>
							<?php
								$producten = ProductModel::getAllProducten(false);
								
								foreach($producten as $product){
									$cat = CategorieModel::getCategorie($product->categorie)->naam;
									$uitgelicht = ($product->uitgelicht == 1)? 'Ja' : 'Nee';
									$actief = ($product->active == 0)? "Nee" : "Ja";

									echo '<tr class="bestellingen-tr admin-bestellingen-tr">';
									echo '<td>' . $product->id . '</td>';
									echo '<td>' . $product->naam . '</td>';
									echo '<td>€ ' . $product->prijs . '</td>';
									echo '<td>' . $cat . '</td>';
									echo '<td>' . $uitgelicht . '</td>';
									echo '<td>' . date("d/m/Y H\ui", strtotime($product->datum_toegevoegd)) . '</td>';
									echo '<td>' . $actief . '</td>';
									echo '<td><a class="bestellingen-details" href="' . URL . 'views/admin/overview.php?product=' . $product->id . '">Details</a></td>';
									echo '</tr>';
								}
							?>
						</table>
					</div>
				</div>
			</div>
			<?php
		}

		if(isset($_GET["product"])){
			$product = ProductModel::getProduct($_GET["product"]);
			if($product){
				?>
				<div class="popup-maincont">
					<div class="popup-cont">
						<div class="popup-titel">
							<?php
							$product = ProductModel::getProduct($_GET["product"]);
							echo $product->naam;
							
							echo '<a href="' . URL . 'views/admin/overview.php" class="popup-close close" title="Sluiten"></a>';
							
							?>
							
						</div>

						<?php if($product->active == 1){ ?>
						<form action="../../controllers/RequestController.php" method="POST">
							<input type="hidden" name="admin-delete-product" value="<?php echo $product->id ?>">
							<input class="popup-delete" type="submit" value="" title="Verwijder dit product">
						</form>

						<?php } ?>

						<form class="popup-content scroll-clean" action="../../controllers/RequestController.php" method="POST" enctype='multipart/form-data'>

							<?php 
								if($product->active == 0) echo '<div class="activate-user">Dit product is niet actief. Klik <a href="../../controllers/RequestController.php?admin-activate-product=' . $_GET["product"] . '">hier</a> om het te activeren</div>';
							?>

							<div class="wrapper-div-input-cont">
								<p class="wrapper-div-input-label">Foto</p>
								<img src="<?php echo URL . 'resources/images/' . $product->img_path ?>" class="wrapper-div-input-input wrapper-div-input-img">
								<div class="clearfix"></div>
								<div class="wrapper-div-input-label">&nbsp;</div>
								<input type="file" class="wrapper-div-input-input" name="edit-product-foto" value="<?php echo $product->img_path ?>">
								<div class="clearfix"></div>
							</div>

							<div class="wrapper-div-input-cont">
								<p class="wrapper-div-input-label">Naam</p>
								<input type="text" class="wrapper-div-input-input wrapper-div-input-text" name="edit-product-naam" value="<?php echo $product->naam ?>">

								<div class="clearfix"></div>
							</div>

							<div class="wrapper-div-input-cont">
								<p class="wrapper-div-input-label">Prijs</p>
								<input type="number" step='0.01' class="wrapper-div-input-input" name="edit-product-prijs" value="<?php echo $product->prijs ?>">

								<div class="clearfix"></div>
							</div>

							<div class="wrapper-div-input-cont">
								<p class="wrapper-div-input-label">Categorie</p>
								<select type="text" class="wrapper-div-input-input" name="edit-product-categorie">
									<?php
										$cats = CategorieModel::getAllCategorien();
										foreach($cats as $categorie){
											if($categorie->id == $product->categorie){
												echo '<option selected value="' . $categorie->id . '">' . $categorie->naam . '</option>';
											}
											else{
												echo '<option value="' . $categorie->id . '">' . $categorie->naam . '</option>';
											}
										}
									?>
								</select>

								<div class="clearfix"></div>
							</div>

							<div class="wrapper-div-input-cont">
								<p class="wrapper-div-input-label">Uitgelicht</p>
								<select type="text" class="wrapper-div-input-input" name="edit-product-uitgelicht">
									<?php
										if($product->uitgelicht == 1){
											echo '<option selected value="true">Ja</option>';
											echo '<option value="false">Nee</option>';
										}
										else{
											echo '<option selected value="false">Nee</option>';
											echo '<option value="true">Ja</option>';
										}
									?>
								</select>

								<div class="clearfix"></div>
							</div>

							<div class="wrapper-div-input-cont">
								<p class="wrapper-div-input-label">Beschrijving</p>
								<textarea class="wrapper-div-input-input scroll-clean wrapper-div-input-textarea" name="edit-product-beschrijving"><?php echo $product->beschrijving ?></textarea>

								<div class="clearfix"></div>
							</div>

							<div style="clear:both;"></div>

							<input type="hidden" name="admin-edit-product" value="<?php echo $product->id ?>">
							<input class='button-blue' type='submit'>
						</form>
					</div>
				</div>
				<?php
			}
		}	

		if(isset($_GET["bestellingen-view"])){
			?>
			<div class="popup-maincont">
				<div class="popup-cont popup-wide">
					<div class="popup-titel">
						Alle bestellingen
						<?php
							echo '<a href="' . URL . 'views/admin/overview.php" class="popup-close close" title="Sluiten"></a>';
						?>
					</div>
					<div class="popup-content scroll-clean">
						<table class="bestellingen-table">
							<tr class="bestellingen-tr">
								<th>ID</th>
								<th>ID koper</th>
								<th>Naam koper</th>
								<th>Datum</th>
								<th>Aantal producten</th>
								<th>Totaal</th>
								<th>Betaalmethode</th>
								<th>Levermethode</th>
							</tr>
							<?php
								$bestellingen = BestellingModel::getAllBestellingen();
								
								foreach($bestellingen as $bestelling){
									$alleProducten = BestellingProductModel::getBestellingProductenIds($bestelling->id);
									$aantal = 0;
									foreach($alleProducten as $product){
										$aantal += $product->aantal;
									}

									$user = UserModel::getUser($bestelling->user_id);
									$betaalmethode = BetaalmethodeModel::getBetaalmethode($bestelling->betaalmethode_id);
									$levermethode = LevermethodeModel::getLevermethode($bestelling->levermethode_id);
									echo '<tr class="bestellingen-tr admin-bestellingen-tr">';
									echo '<td>' . $bestelling->id . '</td>';
									echo '<td>' . $user->id . '</td>';
									echo '<td>' . $user->firstname . " " . $user->lastname . '</td>';
									echo '<td>' . date("d/m/Y H\ui", strtotime($bestelling->datum)) . '</td>';
									echo '<td>' . $aantal . '</td>';
									echo '<td>€ ' . $bestelling->totaal . '</td>';
									echo '<td>' . $betaalmethode->naam . '</td>';
									echo '<td>' . ucfirst($levermethode->naam) . '</td>';
									echo '<td><a class="bestellingen-details" href="' . URL . 'views/bestellingsoverzicht.php?id=' . $bestelling->id . '">Details</a></td>';
									echo '</tr>';
								}
							?>
						</table>
					</div>
				</div>
			</div>
			<?php
		}
		if(isset($_GET["user-add"])){
			?>
			<div class="popup-maincont">
				<div class="popup-cont">
					<div class="popup-titel">
						Gebruiker toevoegen
						<?php
							echo '<a href="' . URL . 'views/admin/overview.php" class="popup-close close" title="Sluiten"></a>';
						?>
					</div>
					
					<form class="popup-content scroll-clean" action="../../controllers/RequestController.php" method="POST" enctype='multipart/form-data'>

						<div class="wrapper-div-input-cont">
							<p class="wrapper-div-input-label">Foto</p>
							<input type="file" class="wrapper-div-input-input" name="add-user-foto">

							<div class="clearfix"></div>
						</div>

						<div class="wrapper-div-input-cont">
							<p class="wrapper-div-input-label">Voornaam</p>
							<input required type="text" class="wrapper-div-input-input wrapper-div-input-text" name="add-user-firstname">

							<div class="clearfix"></div>
						</div>

						<div class="wrapper-div-input-cont">
							<p class="wrapper-div-input-label">Achternaam</p>
							<input required type="text" class="wrapper-div-input-input wrapper-div-input-text" name="add-user-lastname">

							<div class="clearfix"></div>
						</div>

						<div class="wrapper-div-input-cont">
							<p class="wrapper-div-input-label">E-mailadres</p>
							<input required type="email" class="wrapper-div-input-input wrapper-div-input-text" name="add-user-email">

							<div class="clearfix"></div>
						</div>

						<div class="wrapper-div-input-cont">
							<p class="wrapper-div-input-label">Paswoord</p>
							<input required type="password" class="wrapper-div-input-input wrapper-div-input-text" name="add-user-password">

							<div class="clearfix"></div>
						</div>

						<div class="wrapper-div-input-cont">
							<p class="wrapper-div-input-label">Bevestig paswoord</p>
							<input required type="password" class="wrapper-div-input-input wrapper-div-input-text" name="add-user-confirmpassword">

							<div class="clearfix"></div>
						</div>

						<div class="wrapper-div-input-cont">
							<p class="wrapper-div-input-label">Bevoegdheid</p>
							<select type="number" class="wrapper-div-input-input" name="add-user-authority">
								<option value="0">Normale gebruiker</option>
								<option value="1">Beheerder</option>
							</select>

							<div class="clearfix"></div>
						</div>

						<div style="clear:both;"></div>

						<input type="hidden" name="admin-add-user" value="true">
						<input class='button-blue' type='submit'>
					</form>


				</div>
			</div>
			<?php
		}
		if(isset($_GET["user-select"])){
			?>
			<div class="popup-maincont">
				<div class="popup-cont popup-wide">
					<div class="popup-titel">
						Alle gebruikers
						<?php
							echo '<a href="' . URL . 'views/admin/overview.php" class="popup-close close" title="Sluiten"></a>';
						?>
					</div>

					<div class="popup-content scroll-clean">
						<table class="bestellingen-table">
							<tr class="bestellingen-tr">
								<th>ID</th>
								<th>Naam</th>
								<th>E-mailadres</th>
								<th>Bevoegdheid</th>
								<th>Actief</th>
								<th></th>
							</tr>
							<?php
								$users = UserModel::getAllUsers();
								
								foreach($users as $user){
									$actief = ($user->active == 0)? "Nee" : "Ja";
									$authority = ($user->authority == 0)? "Normale gebruiker" : "Beheerder";
									echo '<tr class="bestellingen-tr admin-bestellingen-tr">';
									echo '<td>' . $user->id . '</td>';
									echo '<td>' . $user->firstname . " " . $user->lastname . '</td>';
									echo '<td>' . $user->email . '</td>';
									echo '<td>' . $authority . '</td>';
									echo '<td>' . $actief . '</td>';
									echo '<td><a class="bestellingen-details" href="overview.php?user-id=' . $user->id . '">Details</a></td>';
									echo '</tr>';
								}
							?>
						</table>
					</div>
				</div>
			</div>
			<?php
		}
		if(isset($_GET["user-id"])){
			$user = UserModel::getUser($_GET["user-id"]);
			if($user){
				?>
				<div class="popup-maincont">
					<div class="popup-cont">
						<div class="popup-titel">
							<?php
								echo "Bewerk '" . $user->firstname . " " . $user->lastname . "'";
								echo '<a href="' . URL . 'views/admin/overview.php" class="popup-close close" title="Sluiten"></a>';
							?>
						</div>

						<?php 
							if($user->active == 1){
								?>
								<form action="../../controllers/RequestController.php" method="POST">
									<input type="hidden" name="admin-delete-user" value="<?php echo $user->id ?>">
									<input class="popup-delete" type="submit" value="" title="Verwijder deze gebruiker">
								</form>
							<?php
							} 
						?>
						
						<form class="popup-content scroll-clean" action="../../controllers/RequestController.php" method="POST" enctype='multipart/form-data'>
							<?php 
								if($user->active == 0) echo '<div class="activate-user">Deze user is niet actief. Klik <a href="../../controllers/RequestController.php?admin-activate-user=' . $_GET["user-id"] . '">hier</a> om hem te activeren</div>';
							?>
							<div class="wrapper-div-input-cont">
								<p class="wrapper-div-input-label">Foto</p>
								<img src="<?php echo URL . 'resources/images/avatars/' . $user->img_path ?>" class="wrapper-div-input-input wrapper-div-input-img">
								<div class="clearfix"></div>
								<div class="wrapper-div-input-label">&nbsp;</div>
								<input type="file" class="wrapper-div-input-input" name="edit-user-foto">

								<div class="clearfix"></div>
							</div>

							<div class="wrapper-div-input-cont">
								<p class="wrapper-div-input-label">Voornaam</p>
								<input required type="text" class="wrapper-div-input-input wrapper-div-input-text" name="edit-user-firstname" value="<?php echo $user->firstname ?>">

								<div class="clearfix"></div>
							</div>

							<div class="wrapper-div-input-cont">
								<p class="wrapper-div-input-label">Achternaam</p>
								<input required type="text" class="wrapper-div-input-input wrapper-div-input-text" name="edit-user-lastname" value="<?php echo $user->lastname ?>">

								<div class="clearfix"></div>
							</div>

							<div class="wrapper-div-input-cont">
								<p class="wrapper-div-input-label">E-mailadres</p>
								<input required type="email" class="wrapper-div-input-input wrapper-div-input-text" name="edit-user-email" value="<?php echo $user->email ?>">

								<div class="clearfix"></div>
							</div>

							<div class="wrapper-div-input-cont">
								<p class="wrapper-div-input-label">Paswoord</p>
								<input required type="password" class="wrapper-div-input-input wrapper-div-input-text" name="edit-user-password" value="<?php echo $user->password ?>">

								<div class="clearfix"></div>
							</div>

							<div class="wrapper-div-input-cont">
								<p class="wrapper-div-input-label">Bevestig paswoord</p>
								<input required type="password" class="wrapper-div-input-input wrapper-div-input-text" name="edit-user-confirmpassword" value="<?php echo $user->password ?>">

								<div class="clearfix"></div>
							</div>

							<div class="wrapper-div-input-cont">
								<p class="wrapper-div-input-label">Bevoegdheid</p>
								<select type="number" class="wrapper-div-input-input" name="edit-user-authority">
									<option <?php echo ($user->authority == 0)? "selected" : "" ?> value="0">Normale gebruiker</option>
									<option <?php echo ($user->authority == 1)? "selected" : "" ?> value="1">Beheerder</option>
								</select>

								<div class="clearfix"></div>
							</div>

							<div style="clear:both;"></div>

							<input type="hidden" name="admin-edit-user" value="<?php echo $_GET["user-id"] ?>">
							<input class='button-blue' type='submit'>
						</form>


					</div>
				</div>
				<?php
			}
		}
	?>
	
	<?php include("../partials/_scripts.php"); ?>
</body>
</html>