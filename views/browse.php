<?php
	include_once $_SERVER['DOCUMENT_ROOT'] . "/shop/includes.php";

	//Filters
	/*$filters = !empty($_POST["filters"])? $_POST["filters"] : [];*/
	$filtersString = isset($_GET["filters"])? $_GET["filters"] : '';
	$filters = empty($filtersString)? [] : explode(',', $filtersString);

	$perPagina = 12;

	//Bepalen op welke pagina de user zit
	$pagina = isset($_GET['pagina'])? $_GET['pagina'] : 1;

	//Producten initialiseren
	$producten = [];
	if(!empty($filters)){
		foreach($filters as $filter) {
			$cat = CategorieModel::getCategorieByNaam($filter);
		    $producten = array_merge($producten, ProductModel::getAllProductenByCategorie($cat->id));
	    }
	}
	else{
		$producten = ProductModel::getAllProducten();
	}

	//Bepalen hoeveel pagina's er zijn
	$aantalPaginas = ceil(count($producten)/$perPagina);

	$producten = array_slice($producten, ($pagina-1) * $perPagina, $perPagina);
?>

<!doctype HTML>
<html lang="nl">
<head>
	<title>Producten</title>
	<?php include("partials/_stylesheets.php"); ?>
</head>
<body>
	<?php include("partials/_nav.php"); ?>

	<div id="content-cont" class="scroll-clean">
		<div id="main-wrapper">

			<?php include("partials/_notifications.php"); ?>

			<div class="wrapper-titel">
				Alle producten

				<div class="producten-titel-btn" id="producten-titel-btn-sort">Sorteren</div>
				<div class="producten-titel-dropdown" id="producten-titel-dropdown-sort">
					<div class="producten-titel-item producten-titel-item-sort" id="prijs-asc">Prijs oplopend</div>
					<div class="producten-titel-item producten-titel-item-sort" id="prijs-desc">Prijs aflopend</div>
					<div class="producten-titel-item producten-titel-item-sort" id="naam-asc">Naam oplopend</div>
					<div class="producten-titel-item producten-titel-item-sort" id="naam-desc">Naam aflopend</div>
					<div class="producten-titel-item producten-titel-item-sort" id="categorie-asc">Categorie oplopend</div>
					<div class="producten-titel-item producten-titel-item-sort" id="categorie-desc">Categorie aflopend</div>
					<div class="producten-titel-item producten-titel-item-sort" id="datum-asc">Datum oplopend</div>
					<div class="producten-titel-item producten-titel-item-sort" id="datum-desc">Datum aflopend</div>
					<div class="producten-titel-item producten-titel-item-sort" id="beoordeling-asc">Beoordeling oplopend</div>
					<div class="producten-titel-item producten-titel-item-sort" id="beoordeling-desc">Beoordeling aflopend</div>
				</div>

				<div class="producten-titel-btn" id="producten-titel-btn-filter">Filteren</div>
				<div class="producten-titel-dropdown" id="producten-titel-dropdown-filter">
					<form action="../controllers/RequestController.php" method="POST">
					<?php
						$categorien = CategorieModel::getAllCategorien();
						$html = "";
						foreach($categorien as $categorie){
							$html .= "<div class='producten-titel-item producten-titel-item-filter' id='" . $categorie->naam . "'>"
								  . "<input type='checkbox' name='producten-filters[]' class='browse-filter-checkbox' value='" . $categorie->naam . "'>"
								  . "<div class='browse-filter-value'>$categorie->naam</div></div>"
								  . "<input type='hidden' name='producten-filters-page' value='" . $pagina . "'>";
						}
						$html .= "<input type='submit' value='Filter' class='button-blue browse-filter-submit'>";

						echo $html;
					?>
					</form>
				</div>
			</div>
			<div class="producten-cont">
				<?php
					foreach($producten as $product){
						echo ContentController::makeProductHtml($product);
					}
				?>
			</div>

			<div id='pagina-cont'>
				<div id='pagina-subcont'>
					<?php
						if($aantalPaginas > 1) echo ContentController::makePaginasHtml($aantalPaginas, $pagina, $filters);
					?>
				</div>
			</div>

		</div>
	</div>
	
	<?php include("partials/_scripts.php"); ?>
	<script type="text/javascript">
        window.filters = <?php echo json_encode($filters) ?>;
    </script>
</body>
</html>