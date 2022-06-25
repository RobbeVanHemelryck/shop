<?php
	include_once $_SERVER['DOCUMENT_ROOT'] . "/includes.php";
	Utils::authorize([0,1], "index.php");
?>

<!doctype HTML>
<html lang="nl">
<head>
	<title>Home</title>
	<?php include("partials/_stylesheets.php"); ?>
</head>
<body>
	<?php include("partials/_nav.php"); ?>
	
	<div id="content-cont">
		<div id="main-wrapper-shadow"></div>
		<div id="main-wrapper">

			<?php include("partials/_notifications.php"); ?>
			
			<div class="wrapper-titel-2">Producten in jouw winkelmandje</div>
			<br>
			<div id="overview-producten-cont">
				<?php
					if(empty($_SESSION["winkelwagen"])){
						echo "<div id='winkelmandje-empty-cont'><div id='winkelmandje-empty-tekst'>Er bevindt zich nog niets in je winkelmandje, niet gemotiveerd vandaag?</div>";
						echo "<img src='../resources/images/shopping-cart-empty.png' id='winkelmandje-empty-img'></div>";
					}
					else{
						$total = 0;
						foreach($_SESSION["winkelwagen"] as $productObj){
							$product = $productObj[0];
							$aantal = $productObj[1];
							$productTotal = $productObj[0]->prijs * $aantal;
							$total += $productTotal;

							$html = "<div class='overview-product-cont'>"
										. "<a class='overview-product-link' href='/views/detail.php?id=" . $product->id . "' title='Bekijk " . $product->naam . "'>"
											. "<img src='/resources/images/" . $product->img_path . "' class='overview-product-thumb'>"
											. "<div class='overview-product-naam'>" . $product->naam . "</div></a>"
										. "<div class='overview-product-prijscont'>"
											. "<div class='overview-product-form'>"
												. "<form action='../controllers/RequestController.php' method='POST'>"
													. "<input type='hidden' value='" . $product->id . "' name='winkelmandje-update-id'>"
													. "<input type='number' min='0' value='" . $aantal . "' name='overview-update-aantal' class='overview-product-form-input'>"
													. "<div class='overview-product-form-prijs'>x € " . $product->prijs . "</div>"
													. "<input type='submit' value='Aanpassen' class='overview-product-form-submit'>"
												. "</form>"
											. "</div>"
											. "<div class='overview-product-totaalprijs'><tot>Totaal</tot><prijs>€ " . $productTotal . "</prijs></div>"
										. "</div>"
										. "<form action='/controllers/RequestController.php' method='POST'>"
											. "<input type='hidden' value='" . $product->id . "' name='winkelmandje-delete-id'>"
											. "<input type='submit' class='overview-product-delete' value='Verwijder'>"
										. "</form>"
									. "</div>";
							echo $html;
						}

						echo "<div id='overview-total-cont'><div id='overview-total-total'>Totaal</div><div id='overview-total-amount'>€ " . $total . "</div></div></div>";

						echo '<div class="clearfix"></div>';

						if(!isset($_GET["afrekenen"])){
							echo "<a href='/views/winkelmandje.php?afrekenen' id='overview-afrekenen' class='button-green'>Afrekenen</a>";
						}
						else{
							?>

							<div class="wrapper-div" id="wrapper-div-box-maincont">
								<div class="wrapper-div-subcont">
									<div class="wrapper-div-titel">Afrekenen</div>
									<form action="../controllers/RequestController.php" method="POST">

										<div class="wrapper-div-box">
											<div class="wrapper-div-box-titel">Leveradres</div>
											<p class="wrapper-div-box-input-label">Straat</p>
											<input required type="text" class="wrapper-div-box-input" name="lever-straat">

											<div class="clearfix"></div>
											
											<p class="wrapper-div-box-input-label">Huisnummer</p>
											<input required type="text" class="wrapper-div-box-input" name="lever-huisnummer">

											<div class="clearfix"></div>

											<p class="wrapper-div-box-input-label">Gemeente</p>
											<input required type="text" class="wrapper-div-box-input" name="lever-gemeente">

											<div class="clearfix"></div>

											<p class="wrapper-div-box-input-label">Postcode</p>
											<div class="clearfix"></div>
											<input pattern=".{4}" required type="text" class="wrapper-div-box-input-number" name="lever-postcode" title="Gelieve een geldige postcode in te geven">
										</div>

										<div class="wrapper-div-box">
											<div class="wrapper-div-box-titel">Factuuradres</div>
											<p class="wrapper-div-box-input-label">Zelfde als leveradres</p>
											<input type="checkbox" class="wrapper-div-box-input-checkbox" id="zelfdeAdres" name="factuur-checkbox" value="true">

											<div class="clearfix"></div>

											<p class="wrapper-div-box-input-label factuur-input-other">Straat</p>
											<input required type="text" class="wrapper-div-box-input factuur-input-other" name="factuur-straat">

											<p class="wrapper-div-box-input-label factuur-input-other">Huisnummer</p>
											<input required type="text" class="wrapper-div-box-input factuur-input-other" name="factuur-huisnummer">

											<p class="wrapper-div-box-input-label factuur-input-other">Gemeente</p>
											<input required type="text" class="wrapper-div-box-input factuur-input-other" name="factuur-gemeente">

											<p class="wrapper-div-box-input-label factuur-input-other">Postcode</p>
											<div class="clearfix"></div>
											<input pattern=".{4}" required type="text" class="wrapper-div-box-input-number factuur-input-other" name="factuur-postcode" title="Gelieve een geldige postcode in te geven">
										</div>

										<div class="clearfix"></div>

										<div class="wrapper-div-box">
											<div class="wrapper-div-box-titel">Betaalmethode</div>

											<?php  
												$methods = BetaalmethodeModel::getAllBetaalmethoden();
												foreach($methods as $betaalmethode){
													echo '<div class="wrapper-div-box-input-cont">';
													echo '<input required type="radio" class="wrapper-div-box-input-radio" name="betaalmethode" value="' . $betaalmethode->id . '">';
													echo '<img class="wrapper-div-box-radio-thumb" src="../resources/images/' . $betaalmethode->img_path . '">';
													echo '</div>';
													echo '<div class="clearfix"></div>';
												}
											?>
										</div>

										<div class="wrapper-div-box">
											<div class="wrapper-div-box-titel">Levermethode</div>
											<?php  
												$methods = LevermethodeModel::getAllLevermethoden();
												foreach($methods as $levermethode){
													$kost = ($levermethode->kosten_geld == 0)? "gratis" : '€ ' . $levermethode->kosten_geld;
													echo '<div class="wrapper-div-box-input-cont">';
													echo '<input required type="radio" class="wrapper-div-box-input-radio" name="levermethode" value="' . $levermethode->id . '">';
													echo '<div class="wrapper-div-box-radio-label">' . ucfirst($levermethode->naam) . ' (' . $levermethode->duur . ' dagen, <b>' . $kost . '</b>)</div>';
													echo '</div>';
													echo '<div class="clearfix"></div>';
												}
											?>
										</div>

										<div class="clearfix"></div>

										<div id="afrekenen-terms-cont">
											<div id="afrekenen-terms-subcont">
												<p class="wrapper-div-box-input-label">Hierbij ga ik akkoord met de <b><a href="<?php echo URL ?>algemenevoorwaarden.php" title="Bekijk de algemene voorwaarden">algemene voorwaarden</a></b></p>
												<input required type="checkbox" class="wrapper-div-box-input-checkbox" name="afrekenen-akkoord">
												<div class="clearfix"></div>
											</div>
										</div>

										<input type="hidden" name="afrekenen">
										<input class='button-blue' type='submit'>
									</form>
									<div class="clearfix"></div>
								</div>
							</div>

							<?php
						}
					}
					
				?>

		</div>
	</div>

	<?php include("partials/_scripts.php"); ?>
</body>
</html>