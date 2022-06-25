<?php
/*
Deze klasse dient voor het groeperen van dynamische HTML code
--> 1 functie voor zowel GET's via PHP als via AJAX
*/

include_once $_SERVER['DOCUMENT_ROOT'] . "/includes.php";

class ContentController{
	static function makeProductHtml($product){
		$cat = CategorieModel::getCategorie($product->categorie);
		$html = "<div class='product-cont-main'>"
				. "<a href='" . URL . "views/detail.php?id=" . $product->id . "' class='product-cont-sub'>"
			 	 . "<div class='img-height-helper'><img src='" . URL . "resources/images/" . $product->img_path . "' class='product-thumbnail' alt='Deze afbeelding kon niet gevonden worden'></div><hr-small></hr-small>"
				 . "<div class='product-caption scroll-none'>"
					 . "<h4>" . $product->naam . "</h4>"
					 . "<p>" . $cat->naam . "</p>"
				 . "</div>"
				 . "<form action='../controllers/RequestController.php' method='POST'>"
					 . "<input type='hidden' name='winkelwagenAdd' value='" . $product->id . "'>"
					 . "<input type='submit' value='' class='winkelwagen-btn winkelwagen-add' title='Voeg toe aan winkelmandje'>"
				 . "</form>"
				 . "<div class='metadata product-metadata-id'>" . $product->id . "</div>"
				 . "<div class='metadata product-metadata-prijs'>" . $product->prijs . "</div>"
				 . "<div class='metadata product-metadata-naam'>" . $product->naam . "</div>"
				 . "<div class='product-prijs'>€ " . $product->prijs . "</div>";
		
		/*Ratings bepalen*/
		$rating = $product->rating;
		$html .= "<div class='product-stars-cont' title='" . round($rating, 2) . "/10 (" . $product->aantal_ratings . ")'>";
		
		if($product->aantal_ratings > 0){
			for($j = 0; $j < 5; $j++){
				if($rating > 1.5) $html .= "<img src='" . URL . "resources/images/star-full.png' class='product-star'>";
				else if($rating > 0.5 && $rating <= 1.5) $html .= "<img src='" . URL . "resources/images/star-half.png' class='product-star'>";
				else if($rating <= 0.5) $html .= "<img src='" . URL . "resources/images/star-empty.png' class='product-star'>";
				$rating -= 2;
			}
		}

		$html .= "</div></a></div>";
		return $html;
	}

	static function makeWinkelmandjePreviewHtml(){
		$html = "<a href='" . URL . "views/winkelmandje.php' class='nav-winkelmandje-dropdown-item' id='nav-winkelmandje-dropdown-item-first'>Bekijk volledig winkelmandje</a>";
				
		if(isset($_SESSION["winkelwagen"]) == false ) $_SESSION["winkelwagen"] = [];
		$producten = $_SESSION["winkelwagen"];
		$total = 0;
		
		$html .= "<div id='nav-winkelmandje-dropdown-item-cont' class='scroll-none'>";
		
		foreach($producten as $productObj){
			$product = $productObj[0];
			$aantal = $productObj[1];
			$productTotal = $productObj[0]->prijs * $aantal;

			$total += $productTotal;
			$html .= "<div class='nav-winkelmandje-dropdown-item'>"
				  . "<div class='nav-winkelmandje-dropdown-item-naam scroll-none'>";

			if($aantal != 1) $html .= $aantal . "x ";
			
			$html .= $product->naam . "</div>"
			      . "<div class='nav-winkelmandje-dropdown-item-prijs' title='" . $aantal . "x €" . $product->prijs . "/stuk'>" . $productTotal . "</div>"
				  . "</div>";
		}

		$html .= "</div>";

		$html .= "<div id='nav-winkelmandje-dropdown-item-last' class='nav-winkelmandje-dropdown-item'>"
				   . "<div class='nav-winkelmandje-dropdown-item-naam scroll-none'>Totaal</div>"
				   . "<div id='nav-winkelmandje-dropdown-total' class='nav-winkelmandje-dropdown-item-prijs'>" . $total . "</div>"
			   . "</div>";
			   
		return $html;
	}

	static function makePaginasHtml($aantalPaginas, $currentPage, $filters){
		$filtersString = Utils::arrayToUrl($filters);
		$html = '';
		for($i = 1; $i <= $aantalPaginas; $i++){
			$html .= '<form action="browse?filters=' . $filtersString . '&pagina=' . $i . '" method="POST">';
			foreach($filters as $filter){
				$html .= "<input type='hidden' name='filters[]' value='" . $filter . "'>";
			}
			if($i != $currentPage){
				$html .= '<input type="submit" class="pagina-nummer" value="' . $i . '">';
			}
			else{
				$html .= '<input type="submit" class="pagina-nummer pagina-nummer-current" value="' . $i . '">';
			}
			$html .= '</form>';
		}
		return $html;
	}
}

?>