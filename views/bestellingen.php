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
		
			<div class="wrapper-titel">Jouw bestellingen</div>
			<br>
			<?php

				$bestellingen = [];
				$bestellingenBeta = BestellingModel::getAllBestellingenByUserId($_SESSION["user"]->id);

				foreach($bestellingenBeta as $bestelling){
					
					$bestellingObj = [];
					$bestellingObj["data"] = $bestelling;
					$bestellingObj["producten"] = [];

					$bestellingProducten = BestellingProductModel::getBestellingProductenIds($bestelling->id);
					foreach($bestellingProducten as $bestellingProduct){
						$productObj = [];
						$productObj["product"] = ProductModel::getProduct($bestellingProduct->productId);
						$productObj["aantal"] = $bestellingProduct->aantal;

						array_push($bestellingObj["producten"], $productObj);
					}
					
					array_push($bestellingen, $bestellingObj);
				}

				if(empty($bestellingen)){
					echo "<div id='winkelmandje-empty-cont'><div id='winkelmandje-empty-tekst'>Je hebt nog geen bestellingen geplaatst</div>";
					echo "<img src='../resources/images/shopping-cart-empty.png' id='winkelmandje-empty-img'></div>";
				}
				else{
					?>
					<table class="bestellingen-table">
						<tr class="bestellingen-tr">
							<th>Order</th>
							<th>Datum</th>
							<th>Aantal producten</th>
							<th>Totaal</th>
							<th>Betaalmethode</th>
							<th>Levermethode</th>
						</tr>
					<?php
						foreach($bestellingen as $bestellingObj){
							$bestelling = $bestellingObj["data"];
							$alleProducten = BestellingProductModel::getBestellingProductenIds($bestelling->id);
							$aantal = 0;
							foreach($alleProducten as $product){
								$aantal += $product->aantal;
							}

							$betaalmethode = BetaalmethodeModel::getBetaalmethode($bestelling->betaalmethode_id);
							$levermethode = LevermethodeModel::getLevermethode($bestelling->levermethode_id);
							echo '<tr class="bestellingen-tr">';
							echo '<td>' . $bestelling->id . '</td>';
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
			<?php 
				} 
			?>
		</div>
	</div>

	<?php include("partials/_scripts.php"); ?>
</body>
</html>