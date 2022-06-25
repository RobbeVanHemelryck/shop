<?php
	include_once $_SERVER['DOCUMENT_ROOT'] . "/shop/includes.php";
	$bestelling = BestellingModel::getBestelling($_GET['id']);
	Utils::authorize([], "index.php", $bestelling->user_id);
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
		
			<?php

				$bestellingBeta = BestellingModel::getBestelling($_GET["id"]);
					
				$bestelling = ["data" => $bestellingBeta, "producten" => []];

				$bestellingProducten = BestellingProductModel::getBestellingProductenIds($bestellingBeta->id);

				foreach($bestellingProducten as $bestellingProduct){
					$productObj = [
						"product" => ProductModel::getProduct($bestellingProduct->productId),
						"aantal" => $bestellingProduct->aantal
					];
					
					array_push($bestelling["producten"], $productObj);
				}

				echo '<div class="wrapper-titel-2">Bestelling <b>' . $bestelling["data"]->id . '</b></div><br>';

				echo "<div class='wrapper-titel'>" . date("d/m/Y", strtotime($bestelling["data"]->datum)) . " om " . date("H\ui", strtotime($bestelling["data"]->datum)) . "<div class='bestelling-total'>€ " . $bestelling["data"]->totaal . "</div></div>";
				echo "<div class='bestelling-cont'>";
				
				foreach($bestelling["producten"] as $productObj){
					$product = $productObj["product"];
					$aantal = $productObj["aantal"];
					$productTotal = $product->prijs * $aantal;

					$html = "<div class='overview-product-cont'>"
								. "<a class='overview-product-link' href='" . URL . "views/detail.php?id=" . $product->id . "' title='Bekijk " . $product->naam . "'>"
									. "<img src='" . URL . "resources/images/" . $product->img_path . "' class='overview-product-thumb'>"
									. "<div class='overview-product-naam'>" . $product->naam . "</div></a>"
								. "<div class='overview-product-prijscont'>"
									. "<div class='bestelling-product-prijsdata'><tekst>Aantal</tekst><data>" . $aantal . "</data></div>"
									. "<div class='bestelling-product-prijsdata'><tekst>Prijs/stuk</tekst><data>€ " . $product->prijs . "</data></div>"
									. "<div class='bestelling-product-totaalprijs'><data>€ " . $productTotal . "</data></div>"
								. "</div>"
							. "</div>";
					echo $html;
				}

				echo "</div>";
				
				
			?>

			<div class="wrapper-div" id="wrapper-div-box-maincont">
				<div class="wrapper-div-subcont">
					<div class="wrapper-div-titel">Details</div>
					<form action="../controllers/RequestController.php" method="POST">

						<div class="wrapper-div-box">
							<div class="wrapper-div-box-titel">Leveradres</div>
							<p class="wrapper-div-box-input-label">Straat</p>
							<input disabled class="wrapper-div-box-input" value="<?php echo $bestelling['data']->lever_straat ?>">

							<div class="clearfix"></div>
							
							<p class="wrapper-div-box-input-label">Huisnummer</p>
							<input disabled class="wrapper-div-box-input" value="<?php echo $bestelling['data']->lever_huisnummer ?>">

							<div class="clearfix"></div>

							<p class="wrapper-div-box-input-label">Gemeente</p>
							<input disabled class="wrapper-div-box-input" value="<?php echo $bestelling['data']->lever_gemeente ?>">

							<div class="clearfix"></div>

							<p class="wrapper-div-box-input-label">Postcode</p>
							<div class="clearfix"></div>
							<input disabled class="wrapper-div-box-input-number" value="<?php echo $bestelling['data']->lever_postcode ?>">
						</div>

						<div class="wrapper-div-box">
							<div class="wrapper-div-box-titel">Factuuradres</div>

							<p class="wrapper-div-box-input-label factuur-input-other">Straat</p>
							<input disabled class="wrapper-div-box-input factuur-input-other" value="<?php echo $bestelling['data']->factuur_straat ?>">

							<p class="wrapper-div-box-input-label factuur-input-other">Huisnummer</p>
							<input disabled class="wrapper-div-box-input factuur-input-other" value="<?php echo $bestelling['data']->factuur_huisnummer ?>">

							<p class="wrapper-div-box-input-label factuur-input-other">Gemeente</p>
							<input disabled class="wrapper-div-box-input factuur-input-other" value="<?php echo $bestelling['data']->factuur_gemeente ?>">

							<p class="wrapper-div-box-input-label factuur-input-other">Postcode</p>
							<div class="clearfix"></div>
							<input disabled class="wrapper-div-box-input-number factuur-input-other" value="<?php echo $bestelling['data']->factuur_postcode ?>">
						</div>

						<div class="clearfix"></div>

						<div class="wrapper-div-box">
							<div class="wrapper-div-box-titel">Betaalmethode</div>

							<?php  
								$methods = BetaalmethodeModel::getAllBetaalmethoden();
								foreach($methods as $betaalmethode){
									$checked = ($bestelling["data"]->betaalmethode_id == $betaalmethode->id)? "checked" : "";
									echo '<div class="wrapper-div-box-input-cont">';
									echo '<input ' . $checked . ' disabled required type="radio" class="wrapper-div-box-input-radio" name="betaalmethode" value="' . $betaalmethode->id . '">';
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
									$checked = ($bestelling["data"]->levermethode_id == $levermethode->id)? "checked" : "";
									$kost = ($levermethode->kosten_geld == 0)? "gratis" : '€ ' . $levermethode->kosten_geld;
									echo '<div class="wrapper-div-box-input-cont">';
									echo '<input ' . $checked . ' disabled required type="radio" class="wrapper-div-box-input-radio" name="levermethode" value="' . $levermethode->id . '">';
									echo '<div class="wrapper-div-box-radio-label">' . ucfirst($levermethode->naam) . ' (' . $levermethode->duur . ' dagen, <b>' . $kost . '</b>)</div>';
									echo '</div>';
									echo '<div class="clearfix"></div>';
								}
							?>
						</div>

					</form>
					<div class="clearfix"></div>
				</div>
			</div>

		</div>
	</div>

	<?php include("partials/_scripts.php"); ?>
</body>
</html>