<?php
	include_once $_SERVER['DOCUMENT_ROOT'] . "/includes.php";
?>

<!doctype HTML>
<html lang="nl">
<head>
	<title>Producten</title>
	<?php include("views/partials/_stylesheets.php"); ?>
</head>
<body>
	<?php include("views/partials/_nav.php"); ?>

	<div id="content-cont" class="scroll-clean">
		<div id="main-wrapper">

			<?php include("views/partials/_notifications.php"); ?>

			<?php
				if(isset($_GET['q'])){
					$zoekdata = LogicController::search($_GET['q']);
					if(!empty($zoekdata['naam']) || !empty($zoekdata['beschrijving']) || !empty($zoekdata['categorie'])){
						if(!empty($zoekdata["categorie"])){
							echo '<div class="wrapper-titel">Categorie \'' . $_GET['q'] . '\'</div>';
							foreach($zoekdata["categorie"] as $productId){
								$product = ProductModel::getProduct($productId);
								echo ContentController::makeProductHtml($product);
							}
						}

						if(!empty($zoekdata["naam"])){
							echo '<div class="wrapper-titel">Resultaten voor \'' . $_GET['q'] . '\'</div>';
							foreach($zoekdata["naam"] as $productId){
								$product = ProductModel::getProduct($productId);
								echo ContentController::makeProductHtml($product);
							}
						}

						if(!empty($zoekdata["beschrijving"])){
							if(!empty($zoekdata["naam"])){
								echo '<div class="wrapper-titel">Overige resultaten</div>';
							}
							else{
								echo '<div class="wrapper-titel">Resultaten voor \'' . $_GET['q'] . '\'</div>';
							}
							foreach($zoekdata["beschrijving"] as $productId){
								$product = ProductModel::getProduct($productId);
								echo ContentController::makeProductHtml($product);
							}
						}
					}
					
					else{
						echo "<div id='winkelmandje-empty-cont'><div id='winkelmandje-empty-tekst'>'" . $_GET['q'] . "' leverde geen resultaten op</div>";
						echo "<img src='resources/images/shopping-cart-empty.png' id='winkelmandje-empty-img'></div>";
					}

					
				}
				else{
					header('Location: ' . URL);
				}
			?>

		</div>
	</div>
	
	<?php include("views/partials/_scripts.php"); ?>
	<script type="text/javascript">
        window.filters = <?php echo (!empty($_POST["filters"])?json_encode($_POST["filters"]):json_encode([])) ?>;
    </script>
</body>
</html>